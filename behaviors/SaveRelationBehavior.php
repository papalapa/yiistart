<?php

    namespace papalapa\yiistart\behaviors;

    use Yii;
    use yii\base\Behavior;
    use yii\base\Exception;
    use yii\base\InvalidParamException;
    use yii\base\ModelEvent;
    use yii\db\ActiveQueryInterface;
    use yii\db\ActiveRecord;
    use yii\db\Transaction;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Inflector;

    /**
     * Class SaveRelationBehavior
     * This Active Record Behavior allows to validate and save the Model relations when the save() method is invoked.
     * @package papalapa\yiistart\behaviors
     */
    class SaveRelationBehavior extends Behavior
    {
        /**
         * @var ActiveRecord
         */
        public $owner;
        /**
         * Needed relations
         * @var array
         */
        public  $relations             = [];
        private $_oldRelationValue     = [];
        private $_relationsSaveStarted = false;
        private $_transaction;

        /**
         * @return array
         */
        public function events()
        {
            return [
                ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
                ActiveRecord::EVENT_AFTER_INSERT    => 'afterSave',
                ActiveRecord::EVENT_AFTER_UPDATE    => 'afterSave',
            ];
        }

        /**
         * Check if the behavior is attached to an Active Record
         * @param ActiveRecord $owner
         * @throws InvalidParamException
         */
        public function attach($owner)
        {
            if (!($owner instanceof ActiveRecord)) {
                throw new InvalidParamException('Owner must be instance of ' . ActiveRecord::className());
            }
            parent::attach($owner);
        }

        /**
         * Run validating of current relation models before model validate is triggered
         * @param ModelEvent $event
         */
        public function beforeValidate(ModelEvent $event)
        {
            if ($this->_relationsSaveStarted == false && !empty($this->_oldRelationValue)) {
                if ($this->_saveRelatedRecords($this->owner, $event)) {
                    foreach ($this->relations as $relationName) {
                        if (array_key_exists($relationName, $this->_oldRelationValue)) {
                            $relation = $this->owner->getRelation($relationName);
                            if ($relation->multiple === false && !empty($this->owner->{$relationName})) {
                                Yii::trace("Setting foreign keys for {$relationName}", __METHOD__);
                                foreach ($relation->link as $relatedAttribute => $modelAttribute) {
                                    if ($this->owner->{$modelAttribute} !== $this->owner->{$relationName}->{$relatedAttribute}) {
                                        $this->owner->{$modelAttribute} = $this->owner->{$relationName}->{$relatedAttribute};
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        /**
         * Link the related models.
         * If the models have not been changed, nothing will be done.
         * Related records will be linked to the owner model using the ActiveRecord `link()` method.
         */
        public function afterSave()
        {
            if ($this->_relationsSaveStarted == false && $this->_relationsSaveStarted = true) {
                foreach ($this->relations as $relationName) {
                    if (array_key_exists($relationName, $this->_oldRelationValue)) {
                        Yii::trace("Linking {$relationName} relation", __METHOD__);
                        $relation = $this->owner->getRelation($relationName);
                        if ($relation->multiple === true) { // Has many relation
                            // Process new relations
                            $existingRecords = [];
                            /** @var ActiveRecord $relationModel */
                            foreach ($this->owner->{$relationName} as $relationModel) {
                                if ($relationModel->isNewRecord) {
                                    if ($relation->via !== null) {
                                        $relationModel->save(false);
                                    }
                                    $this->owner->link($relationName, $relationModel);
                                } else {
                                    $existingRecords[] = $relationModel;
                                }
                                if (count($relationModel->dirtyAttributes)) {
                                    $relationModel->save(false);
                                }
                            }
                            // Process existing added and deleted relations
                            list($addedPks, $deletedPks) = $this->_computePkDiff($this->_oldRelationValue[$relationName], $existingRecords);
                            // Deleted relations
                            $initialModels = ArrayHelper::index($this->_oldRelationValue[$relationName], function (ActiveRecord $model) {
                                return implode("-", $model->getPrimaryKey(true));
                            });
                            foreach ($deletedPks as $key) {
                                $this->owner->unlink($relationName, $initialModels[$key], true);
                            }
                            // Added relations
                            $actualModels = ArrayHelper::index($this->owner->{$relationName}, function (ActiveRecord $model) {
                                return implode("-", $model->getPrimaryKey(true));
                            });
                            foreach ($addedPks as $key) {
                                $this->owner->link($relationName, $actualModels[$key]);
                            }
                        } else { // Has one relation
                            if ($this->_oldRelationValue[$relationName] !== $this->owner->{$relationName}) {
                                if ($this->owner->{$relationName} instanceof ActiveRecord) {
                                    $this->owner->link($relationName, $this->owner->{$relationName});
                                } else {
                                    if ($this->_oldRelationValue[$relationName] instanceof ActiveRecord) {
                                        $this->owner->unlink($relationName, $this->_oldRelationValue[$relationName]);
                                    }
                                }
                            }
                        }
                        unset($this->_oldRelationValue[$relationName]);
                    }
                }
                $this->owner->refresh();
                $this->_relationsSaveStarted = false;
                if (($this->_transaction instanceof Transaction) && $this->_transaction->isActive) {
                    $this->_transaction->commit();
                }
            }
        }

        /**
         * Override canSetProperty method to be able to detect if a relation setter is allowed.
         * Setter is allowed if the relation is declared in the `relations` parameter
         * @param string  $name
         * @param boolean $checkVars
         * @return boolean
         */
        public function canSetProperty($name, $checkVars = true)
        {
            $getter = 'get' . $name;
            if (in_array($name, $this->relations)
                && method_exists($this->owner, $getter)
                && $this->owner->$getter() instanceof ActiveQueryInterface
            ) {
                return true;
            }

            return parent::canSetProperty($name, $checkVars);
        }

        /**
         * Override __set method to be able to set relations values either by providing a model instance,
         * a primary key value or an associative array
         * @param string $name
         * @param mixed  $value
         */
        public function __set($name, $value)
        {
            if (in_array($name, $this->relations)) {
                /** @var ActiveRecord $model */
                $model = $this->owner;
                Yii::trace("Setting {$name} relation value", __METHOD__);
                /** @var \yii\db\ActiveQuery $relation */
                $relation = $model->getRelation($name);
                if (!isset($this->_oldRelationValue[$name])) {
                    Yii::trace("Initializing old {$name} relation value", __METHOD__);
                    $this->_oldRelationValue[$name] = $this->owner->{$name};
                }
                if ($relation->multiple === true) {
                    $newRelations = [];
                    if (!is_array($value)) {
                        if (!empty($value)) {
                            $value = [$value];
                        } else {
                            $value = [];
                        }
                    }
                    foreach ($value as $entry) {
                        if ($entry instanceof $relation->modelClass) {
                            $newRelations[] = $entry;
                        } else {
                            /* TODO: handle this with one DB request to retrieve all models */
                            $newRelations[] = $this->_processModelAsArray($entry, $relation);
                        }
                    }
                    $model->populateRelation($name, $newRelations);
                } else {
                    if (!($value instanceof $relation->modelClass)) {
                        $value = $this->_processModelAsArray($value, $relation);
                    }
                    $model->populateRelation($name, $value);
                }
            }
        }

        /**
         * Get an ActiveRecord model using the given $data parameter.
         * $data could either be a model ID or an associative array representing model attributes => values
         * @param mixed               $data
         * @param \yii\db\ActiveQuery $relation
         * @return ActiveRecord
         */
        public function _processModelAsArray($data, $relation)
        {
            /** @var ActiveRecord $modelClass */
            $modelClass = $relation->modelClass;
            // Get the related model foreign keys
            if (is_array($data)) {
                $fks = [];
                // Get the right link definition
                if ($relation->via instanceof ActiveRecord) {
                    $viaQuery = $relation->via;
                    $link     = $viaQuery->link;
                } elseif (is_array($relation->via)) {
                    list($viaName, $viaQuery) = $relation->via;
                    $link = $viaQuery->link;
                } else {
                    $link = $relation->link;
                }
                foreach ($link as $relatedAttribute => $modelAttribute) {
                    if (array_key_exists($modelAttribute, $data) && !empty($data[$modelAttribute])) {
                        $fks[$modelAttribute] = $data[$modelAttribute];
                    }
                }
            } else {
                $fks = $data;
            }
            // Load existing model or create one if no key was provided and data is not empty
            /** @var ActiveRecord $relationModel */
            $relationModel = null;
            if (!empty($fks)) {
                $relationModel = $modelClass::findOne($fks);
            }
            if (!($relationModel instanceof ActiveRecord) && !empty($data)) {
                $relationModel = new $modelClass;
            }
            if (($relationModel instanceof ActiveRecord) && is_array($data)) {
                $relationModel->setAttributes($data);
            }

            return $relationModel;
        }

        /**
         * For each related model, try to save it first.
         * If set in the owner model, operation is done in a transactional way so if one of the models should not validate
         * or be saved, a rollback will occur.
         * This is done during the before validation process to be able to set the related foreign keys.
         * @param ActiveRecord $model
         * @param ModelEvent   $event
         * @return bool
         */
        public function _saveRelatedRecords(ActiveRecord $model, ModelEvent $event)
        {
            if (($model->isNewRecord && $model->isTransactional($model::OP_INSERT))
                || (!$model->isNewRecord && $model->isTransactional($model::OP_UPDATE))
                || $model->isTransactional($model::OP_ALL)
            ) {
                $this->_transaction = $model->getDb()->beginTransaction();
            }
            try {
                foreach ($this->relations as $relationName) {
                    if (array_key_exists($relationName, $this->_oldRelationValue)) {
                        $relation = $model->getRelation($relationName);
                        if (!empty($model->{$relationName})) {
                            if ($relation->multiple === false) {
                                // Save Has one relation new record
                                $pettyRelationName = Inflector::camel2words($relationName, true);
                                $this->_saveModelRecord($model->{$relationName}, $event, $pettyRelationName, $relationName);
                            } else {
                                // Save Has many relations new records
                                /** @var ActiveRecord $relationModel */
                                foreach ($model->{$relationName} as $i => $relationModel) {
                                    $pettyRelationName = Inflector::camel2words($relationName, true) . " #{$i}";
                                    $this->_validateRelationModel($pettyRelationName, $relationName, $relationModel, $event);
                                }
                            }
                        }
                    }
                }
                if (!$event->isValid) {
                    throw new Exception("One of the related model could not be validated");
                }
            } catch (Exception $e) {
                if (($this->_transaction instanceof Transaction) && $this->_transaction->isActive) {
                    $this->_transaction->rollBack(); // If anything goes wrong, transaction will be rolled back
                }
                $event->isValid = false; // Stop saving, something went wrong

                return false;
            }

            return true;
        }

        /**
         * Validate and save the model if it is new or changed
         * @param ActiveRecord     $model
         * @param ModelEvent       $event
         * @param                  $pettyRelationName
         * @param                  $relationName
         */
        public function _saveModelRecord(ActiveRecord $model, ModelEvent $event, $pettyRelationName, $relationName)
        {
            $this->_validateRelationModel($pettyRelationName, $relationName, $model, $event);
            if ($event->isValid && (count($model->dirtyAttributes) || $model->isNewRecord)) {
                Yii::trace("Saving {$pettyRelationName} relation model", __METHOD__);
                $model->save(false);
            }
        }

        /**
         * Populates relations with input data
         * @param array       $data
         * @param null|string $formName
         */
        public function loadRelations($data, $formName = null)
        {
            if (!$formName) {
                $formName = $this->owner->formName();
            }
            foreach ($this->relations as $relationName) {
                if (isset($data[$formName][$relationName])) {
                    $this->owner->{$relationName} = $data[$formName][$relationName];
                }
            }
        }

        /**
         * Validate a relation model and add an error message to owner model attribute if needed
         * @param string       $pettyRelationName
         * @param string       $relationName
         * @param ActiveRecord $relationModel
         * @param ModelEvent   $event
         */
        private function _validateRelationModel($pettyRelationName, $relationName, ActiveRecord $relationModel, ModelEvent $event)
        {
            /** @var ActiveRecord $model */
            $model = $this->owner;
            if (!is_null($relationModel) && ($relationModel->isNewRecord || count($relationModel->getDirtyAttributes()))) {
                Yii::trace("Validating {$pettyRelationName} relation model", __METHOD__);
                if (!$relationModel->validate()) {
                    foreach ($relationModel->errors as $attributeErrors) {
                        foreach ($attributeErrors as $error) {
                            $model->addError($relationName, "{$pettyRelationName}: {$error}");
                        }
                        $event->isValid = false;
                    }
                }
            }
        }

        /**
         * Compute the difference between two set of records using primary keys
         * @param ActiveRecord[] $initialRelations
         * @param ActiveRecord[] $updatedRelations
         * @return array
         */
        private function _computePkDiff($initialRelations, $updatedRelations)
        {
            $oldPks = ArrayHelper::getColumn($initialRelations, function (ActiveRecord $model) {
                return implode('-', $model->getPrimaryKey(true));
            });

            $newPks = ArrayHelper::getColumn($updatedRelations, function (ActiveRecord $model) {
                return implode('-', $model->getPrimaryKey(true));
            });

            $identicalPks = array_intersect($oldPks, $newPks);
            $addedPks     = array_values(array_diff($newPks, $identicalPks));
            $deletedPks   = array_values(array_diff($oldPks, $identicalPks));

            return [$addedPks, $deletedPks];
        }
    }
