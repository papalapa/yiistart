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
         * Set model multilingual
         * @var
         */
        public $multilingual = false;
        /**
         * Controller to upload image
         * @var
         */
        public $uploadController;
        /**
         * @var
         */
        public $fileExtensions;
        /**
         * @var
         */
        public $filenamePattern;
        /**
         * Webroot full path
         * For example: /var/www/html/site.domain/frontend/web
         * @var
         */
        public $webroot;
        /**
         * Local path in webroot to upload files
         * For example webroot is /var/www/html/site.domain/frontend/web
         * and savePath will be /var/www/html/site.domain/frontend/web/uploads
         * @var
         */
        public $savePath;
        /**
         * Dirname to upload files
         * For example webroot is /var/www/html/site.domain/frontend/web
         * and savePath is /uploads
         * than saveDir will be /pages
         * @var
         */
        public $saveDir;

        /**
         * @inheritdoc
         */
        public function init()
        {
            parent::init();
            // custom initialization code goes here
        }
    }
