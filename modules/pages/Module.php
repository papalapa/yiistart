<?php

    namespace papalapa\yiistart\modules\pages;

    /**
     * pages module definition class
     */
    class Module extends \yii\base\Module
    {
        /**
         * @inheritdoc
         */
        public $controllerNamespace = 'papalapa\yiistart\modules\pages\controllers';
        /**
         * Use multilingual AR
         * @var
         */
        public $multilingual = false;
        /**
         * Function to validate images
         * @var callable
         */
        public $validateImage;

        /**
         * @inheritdoc
         */
        public function init()
        {
            parent::init();
            // custom initialization code goes here
        }
    }
