<?php

    namespace papalapa\yiistart\modules\photo\controllers;

    use papalapa\yiistart\controllers\MultilingualManageController;
    use papalapa\yiistart\modules\photo\models\Photo;
    use papalapa\yiistart\modules\photo\models\PhotoSearch;

    /**
     * Class DefaultController
     * @package backend\modules\photo\controllers
     */
    class DefaultController extends MultilingualManageController
    {
        /**
         * @var array
         */
        protected $permissions = [
            'create' => 'createPhoto',
            'view'   => 'viewPhoto',
            'update' => 'updatePhoto',
            'index'  => 'indexPhoto',
            'delete' => 'deletePhoto',
        ];

        /**
         * @inheritdoc
         */
        public function init()
        {
            $this->model       = Photo::className();
            $this->searchModel = PhotoSearch::className();
            parent::init();
        }
    }
