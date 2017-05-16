<?php

    namespace papalapa\yiistart\modules\pages;

    /**
     * Class Module
     * Before using this module run migration papalapa/yiistart/modules/pages/migrations
     * @package papalapa\yiistart\modules\pages
     */
    class Module extends \yii\base\Module
    {
        /**
         * @var string
         */
        public $controllerNamespace = 'papalapa\yiistart\modules\pages\controllers';

        /**
         * @inheritdoc
         */
        public function init()
        {
            parent::init();
            // custom initialization code goes here
        }
    }
