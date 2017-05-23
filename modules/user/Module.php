<?php

    namespace papalapa\yiistart\modules\users;

    /**
     * Class Module
     * @package papalapa\yiistart\modules\users
     */
    class Module extends \yii\base\Module
    {
        /**
         * @inheritdoc
         */
        public $controllerNamespace = 'papalapa\yiistart\modules\users\controllers';

        /**
         * @inheritdoc
         */
        public function init()
        {
            parent::init();
            // custom initialization code goes here
        }
    }
