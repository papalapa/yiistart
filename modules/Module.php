<?php

    namespace papalapa\yiistart\modules;

    /**
     * Class Module
     * Use multilingual models and allow file uploading control
     * @package papalapa\yiistart\modules
     */
    class Module extends \yii\base\Module
    {
        /**
         * Controller to upload image
         * @var
         */
        public $uploadController = 'upload';
        /**
         * Upload file rule
         * @var array
         */
        public $uploadRule = [['image'], 'file', 'extensions' => ['pdf', 'gif', 'jpeg', 'jpg', 'png'], 'enableClientValidation' => false];

        /**
         * @inheritdoc
         */
        public function init()
        {
            parent::init();
            // custom initialization code goes here
        }
    }
