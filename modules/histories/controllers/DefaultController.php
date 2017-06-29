<?php

    namespace papalapa\yiistart\modules\histories\controllers;

    use papalapa\yiistart\controllers\MultilingualManageController;
    use papalapa\yiistart\modules\histories\models\Histories;
    use papalapa\yiistart\modules\histories\models\HistoriesSearch;

    /**
     * Class DefaultController
     * @package papalapa\yiistart\modules\histories\controllers
     */
    class DefaultController extends MultilingualManageController
    {
        /**
         * @var array
         */
        protected $permissions = [
            'create' => 'createHistory',
            'view'   => 'viewHistory',
            'update' => 'updateHistory',
            'index'  => 'indexHistory',
            'delete' => 'deleteHistory',
        ];

        /**
         * @param \yii\base\Action $action
         * @return bool
         */
        public function beforeAction($action)
        {
            if (parent::beforeAction($action)) {
                $this->model       = Histories::className();
                $this->searchModel = HistoriesSearch::className();

                return true;
            }

            return false;
        }
    }
