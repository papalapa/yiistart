<?php

    namespace papalapa\yiistart\modules\images\controllers;

    use papalapa\yiistart\controllers\MultilingualManageController;
    use papalapa\yiistart\modules\images\models\Images;
    use papalapa\yiistart\modules\images\models\ImagesSearch;

    /**
     * Class DefaultController
     * @package papalapa\yiistart\modules\images\controllers
     */
    class DefaultController extends MultilingualManageController
    {
        /**
         * @var array
         */
        protected $permissions = [
            'create' => 'createImage',
            'view'   => 'viewImage',
            'update' => 'updateImage',
            'index'  => 'indexImage',
            'delete' => 'deleteImage',
        ];

        /**
         * @inheritdoc
         */
        public function init()
        {
            $this->model       = Images::className();
            $this->searchModel = ImagesSearch::className();
            parent::init();
        }
    }
