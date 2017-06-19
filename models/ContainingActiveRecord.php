<?php

    namespace papalapa\yiistart\models;

    use yii\db\ActiveRecord;
    use yii\helpers\Inflector;

    /**
     * Class ContainingActiveRecord
     * @package papalapa\yiistart\models
     */
    class ContainingActiveRecord extends ActiveRecord
    {
        /**
         * @return string
         */
        public static function contentType()
        {
            return Inflector::camel2id((new \ReflectionClass(get_called_class()))->getShortName());
        }
    }
