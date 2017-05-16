<?php

    namespace papalapa\yiistart\modules\elements;

    /**
     * Class Module
     * Before using this module run migration papalapa/yiistart/modules/elements/migrations
     * @package papalapa\yiistart\modules\elements
     */
    class Module extends \yii\base\Module
    {
        /**
         * @inheritdoc
         */
        public $controllerNamespace = 'papalapa\yiistart\modules\elements\controllers';

        /**
         * @inheritdoc
         */
        public function init()
        {
            parent::init();
            // custom initialization code goes here
        }
    }
