<?php

    namespace papalapa\yiistart\traits;

    use papalapa\yiistart\behaviors\SaveRelationBehavior;
    use yii\db\ActiveRecord;

    /**
     * Class SaveRelationTrait
     * @package papalapa\yiistart\traits
     */
    trait SaveRelationTrait
    {
        /**
         * @param      $data
         * @param null $formName
         * @return bool
         */
        public function load($data, $formName = null)
        {
            /* @var $this ActiveRecord|SaveRelationBehavior */
            if (parent::load($data, $formName)) {
                $this->loadRelations($data, $formName);

                return true;
            }

            return false;
        }
    }
