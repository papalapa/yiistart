<?php

    namespace papalapa\yiistart\modules\settings;

    /**
     * Class Module
     * @package papalapa\yiistart\modules\settings
     */
    class Module extends \yii\base\Module
    {
        /**
         * @inheritdoc
         */
        public $controllerNamespace = 'papalapa\yiistart\modules\settings\controllers';

        /**
         * @inheritdoc
         */
        public function init()
        {
            parent::init();
            // custom initialization code goes here
        }
    }
