<?php

    namespace papalapa\yiistart\modules\settings;

    /**
     * Class Module
     * Before using this module run migration @yii/i18n/migrations
     * To create multilingual relation table use migration template file @vendor/papalapa/yiistart/modules/i18n/migrations/templateFile.php
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
