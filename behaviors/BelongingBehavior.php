<?php

    namespace papalapa\yiistart\behaviors;

    use papalapa\yiistart\models\BaseBelonging;
    use papalapa\yiistart\models\ContainingActiveRecord;
    use yii\base\Behavior;
    use yii\base\InvalidConfigException;
    use yii\base\InvalidParamException;
    use yii\db\ActiveRecord;

    /**
     * Class BelongingBehavior
     * @property ContainingActiveRecord $owner
     * @package papalapa\yiistart\behaviors
     */
    abstract class BelongingBehavior extends Behavior
    {
        /**
         * ActiveRecord attribute name to collect items
         * @var string
         */
        public $attribute;
        /**
         * Pattern for matching all of items
         * @var
         */
        public $pattern;
        /**
         * Max items count
         * @var int
         */
        public $maxCount = 0;
        /**
         * Min items count
         * @var int
         */
        public $minCount = 0;
        /**
         * ActiveRecord class name for collect tags
         * @var BaseBelonging
         */
        public $belongingClass;
        public $belongingAttribute;
        public $belongingAttributeRelation   = 'content_type';
        public $belongingAttributeRelationId = 'content_id';
        public $belongingAttributeOrder      = 'order';
        public $belongingAttributeOutdated   = 'is_outdated';

        /**
         * @throws \yii\base\InvalidConfigException
         */
        public function init()
        {
            if (!is_string($this->attribute) || $this->attribute === '') {
                throw new InvalidConfigException('Property "attribute" must be a string.');
            }

            if (!is_int($this->minCount) && !ctype_digit($this->minCount)) {
                throw new InvalidConfigException('Attribute "minCount" must be a positive integer.');
            }

            if (!is_int($this->maxCount) && !ctype_digit($this->maxCount)) {
                throw new InvalidConfigException('Attribute "maxCount" must be a positive integer.');
            }

            if ($this->minCount > $this->maxCount) {
                throw new InvalidParamException('Attribute "minCount" must be less than attribute "maxCount".');
            }

            parent::init();
        }

        /**
         * @return array
         */
        public function events()
        {
            return [
                ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
                ActiveRecord::EVENT_AFTER_INSERT    => 'afterInsert',
                ActiveRecord::EVENT_AFTER_UPDATE    => 'afterUpdate',
                ActiveRecord::EVENT_AFTER_DELETE    => 'afterDelete',
            ];
        }

        /**
         * Preparing tags before model validate
         */
        public function beforeValidate()
        {
            if (!is_null($this->owner->{$this->attribute})) {
                if (is_array($this->owner->{$this->attribute})) {
                    if (!empty($this->owner->{$this->attribute})) {
                        foreach ($this->owner->{$this->attribute} as $key => $item) {
                            if (null === $item = $this->prepareItem($item)) {
                                unset($this->owner->{$this->attribute}[$key]);
                            }
                        }
                    }
                    $this->owner->{$this->attribute} = array_unique($this->owner->{$this->attribute});
                    $this->checkConsistency();
                } else {
                    $this->owner->{$this->attribute} = [];
                }
            }
        }

        /**
         * When model is new, just insert tags in tag table
         * @throws \yii\db\Exception
         */
        public function afterInsert()
        {
            if (!empty($this->owner->{$this->attribute})) {
                foreach ($this->owner->{$this->attribute} as $index => $item) {
                    /* @var $belonging ActiveRecord */
                    $belonging                                        = new $this->belongingClass;
                    $belonging->{$this->belongingAttributeRelation}   = $this->owner->contentType();
                    $belonging->{$this->belongingAttributeRelationId} = $this->owner->primaryKey;
                    $belonging->{$this->belongingAttribute}           = $item;
                    $belonging->{$this->belongingAttributeOrder}      = $index;
                    $belonging->save();
                }
            }
        }

        /**
         * @throws \yii\db\Exception
         */
        public function afterUpdate()
        {
            if (!is_null($this->owner->{$this->attribute})) {
                $this->markOutdated();
                if (!empty($this->owner->{$this->attribute})) {
                    foreach ($this->owner->{$this->attribute} as $index => $item) {

                        /* @var $belongingClass ActiveRecord */
                        $belongingClass = $this->belongingClass;

                        /* find old */
                        $entity = $belongingClass::find()->where([
                            $this->belongingAttributeRelation   => $this->owner->contentType(),
                            $this->belongingAttributeRelationId => $this->owner->getAttribute('id'),
                            $this->belongingAttribute           => $item,
                        ])->one();
                        /* or create new */
                        if (is_null($entity)) {
                            $entity                                        = new $belongingClass;
                            $entity->{$this->belongingAttributeRelation}   = $this->owner->contentType();
                            $entity->{$this->belongingAttributeRelationId} = $this->owner->getAttribute('id');
                            $entity->{$this->belongingAttribute}           = $item;
                        }

                        $entity->{$this->belongingAttributeOrder}    = $index;
                        $entity->{$this->belongingAttributeOutdated} = false;
                        $entity->save();
                    }
                }
                $this->deleteOutdated();
            }
        }

        /**
         * When model deleted, delete tags from tag table
         * @throws \yii\db\Exception
         */
        public function afterDelete()
        {
            $belongingClass = $this->belongingClass;
            $belongingClass::deleteAll([
                $this->belongingAttributeRelation   => $this->owner->contentType(),
                $this->belongingAttributeRelationId => $this->owner->getAttribute('id'),
            ]);
        }

        /**
         * @param $item
         * @return mixed
         */
        abstract protected function prepareItem($item);

        /**
         * Checks items consistency and returns errors
         * @return null
         */
        abstract protected function checkConsistency();

        /**
         * Mark all rows as outdated and drop index to 0
         * @throws \yii\db\Exception
         */
        protected function markOutdated()
        {
            $belongingCLass = $this->belongingClass;
            $belongingCLass::updateAll([$this->belongingAttributeOutdated => true, $this->belongingAttributeOrder => 0], [
                $this->belongingAttributeRelation   => $this->owner->contentType(),
                $this->belongingAttributeRelationId => $this->owner->getAttribute('id'),
            ]);
        }

        /**
         * Delete outdated rows
         * @throws \yii\db\Exception
         */
        protected function deleteOutdated()
        {
            $belongingClass = $this->belongingClass;
            $belongingClass::deleteAll([
                $this->belongingAttributeRelation   => $this->owner->contentType(),
                $this->belongingAttributeRelationId => $this->owner->getAttribute('id'),
                $this->belongingAttributeOutdated   => true,
            ]);
        }
    }
