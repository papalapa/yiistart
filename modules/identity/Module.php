<?php

    namespace papalapa\yiistart\modules\identity;

    /**
     * Class Module
     * @package papalapa\yiistart\modules\identity
     */
    class Module extends \yii\base\Module
    {
        /**
         * @inheritdoc
         */
        public $controllerNamespace = 'papalapa\yiistart\modules\identity\controllers';

        /**
         * @inheritdoc
         */
        public function init()
        {
            parent::init();
            // custom initialization code goes here
        }
    }
