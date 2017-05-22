<?php

    namespace papalapa\yiistart\modules\menu\controllers;

    use papalapa\yiistart\controllers\MultilingualManageController;
    use papalapa\yiistart\modules\menu\models\Menu;
    use papalapa\yiistart\modules\menu\models\MenuSearch;

    /**
     * Class DefaultController
     * @package papalapa\yiistart\modules\menu\controllers
     */
    class DefaultController extends MultilingualManageController
    {
        /**
         * @var array
         */
        protected $permissions = [
            'create' => 'createMenu',
            'view'   => 'viewMenu',
            'update' => 'updateMenu',
            'index'  => 'indexMenu',
            'delete' => 'deleteMenu',
        ];

        /**
         * @inheritdoc
         */
        public function init()
        {
            $this->model       = Menu::className();
            $this->searchModel = MenuSearch::className();

            parent::init();
        }
    }
