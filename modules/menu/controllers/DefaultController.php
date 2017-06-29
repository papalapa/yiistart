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
         * @param \yii\base\Action $action
         * @return bool
         */
        public function beforeAction($action)
        {
            if (parent::beforeAction($action)) {
                $this->model       = Menu::className();
                $this->searchModel = MenuSearch::className();

                return true;
            }

            return false;
        }
    }
