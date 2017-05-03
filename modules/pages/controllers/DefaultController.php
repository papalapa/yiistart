<?php

    namespace papalapa\yiistart\modules\pages\controllers;

    use papalapa\yiistart\controllers\ManageController;
    use papalapa\yiistart\modules\pages\models\Pages;
    use papalapa\yiistart\modules\pages\models\PagesSearch;

    /**
     * Class DefaultController
     * @package backend\modules\pages\controllers
     */
    class DefaultController extends ManageController
    {
        protected $permissions = [
            'create' => 'createPage',
            'view'   => 'viewPage',
            'update' => 'updatePage',
            'index'  => 'indexPage',
            'delete' => 'deletePage',
        ];

        public function init()
        {
            $this->model       = Pages::className();
            $this->searchModel = PagesSearch::className();
            $this->multilingual = $this->module->multilingual;
            parent::init();
        }
    }
