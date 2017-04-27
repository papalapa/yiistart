<?php

    namespace backend\modules\settings;

    /**
     * settings module definition class
     */
    class Module extends \yii\base\Module
    {
        /**
         * @inheritdoc
         */
        public $controllerNamespace = 'backend\modules\settings\controllers';

        /**
         * @inheritdoc
         */
        public function init()
        {
            parent::init();
            // custom initialization code goes here
        }
    }
