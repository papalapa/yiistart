<?php

    namespace papalapa\yiistart\modules\image\controllers;

    use papalapa\yiistart\controllers\ManageController;
    use papalapa\yiistart\modules\image\models\Album;
    use papalapa\yiistart\modules\image\models\AlbumSearch;

    /**
     * Class AlbumController
     * AlbumController implements the CRUD actions for Album model.
     * @package papalapa\yiistart\modules\image\controllers
     */
    class AlbumController extends ManageController
    {
        /**
         * @var array
         */
        protected $permissions = [
            'create' => 'createAlbum',
            'view'   => 'viewAlbum',
            'update' => 'updateAlbum',
            'index'  => 'indexAlbum',
            'delete' => 'deleteAlbum',
        ];

        /**
         * @param \yii\base\Action $action
         * @return bool
         */
        public function beforeAction($action)
        {
            if (parent::beforeAction($action)) {
                $this->model       = Album::className();
                $this->searchModel = AlbumSearch::className();

                return true;
            }

            return false;
        }
    }
