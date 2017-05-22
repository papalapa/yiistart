<?php

    namespace papalapa\yiistart\modules\pages\controllers;

    use papalapa\yiistart\controllers\MultilingualManageController;
    use papalapa\yiistart\modules\pages\models\Pages;
    use papalapa\yiistart\modules\pages\models\PagesSearch;

    /**
     * Class DefaultController
     * @package papalapa\yiistart\modules\pages\controllers
     */
    class DefaultController extends MultilingualManageController
    {
        /**
         * @var array
         */
        protected $permissions = [
            'create' => 'createPage',
            'view'   => 'viewPage',
            'update' => 'updatePage',
            'index'  => 'indexPage',
            'delete' => 'deletePage',
        ];

        /**
         * @inheritdoc
         */
        public function init()
        {
            $this->model       = Pages::className();
            $this->searchModel = PagesSearch::className();

            parent::init();
        }
    }
