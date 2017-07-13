<?php

    namespace papalapa\yiistart\modules\banners\controllers;

    use papalapa\yiistart\controllers\MultilingualManageController;
    use papalapa\yiistart\modules\banners\models\Banners;
    use papalapa\yiistart\modules\banners\models\BannersSearch;

    /**
     * Class DefaultController
     * @package papalapa\yiistart\modules\banners\controllers
     */
    class DefaultController extends MultilingualManageController
    {
        /**
         * @var array
         */
        protected $permissions = [
            'create' => 'createBanner',
            'view'   => 'viewBanner',
            'update' => 'updateBanner',
            'index'  => 'indexBanner',
            'delete' => 'deleteBanner',
        ];

        /**
         * @param \yii\base\Action $action
         * @return bool
         */
        public function beforeAction($action)
        {
            if (parent::beforeAction($action)) {
                $this->model       = Banners::className();
                $this->searchModel = BannersSearch::className();

                return true;
            }

            return false;
        }
    }
