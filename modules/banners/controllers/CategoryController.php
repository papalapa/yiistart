<?php

    namespace papalapa\yiistart\modules\banners\controllers;

    use papalapa\yiistart\controllers\ManageController;
    use papalapa\yiistart\modules\banners\models\BannersCategory;
    use papalapa\yiistart\modules\banners\models\BannersCategorySearch;

    /**
     * Class CategoryController
     * @package papalapa\yiistart\modules\banners\controllers
     */
    class CategoryController extends ManageController
    {
        /**
         * @var array
         */
        protected $permissions = [
            'create' => 'createBannerCategory',
            'view'   => 'viewBannerCategory',
            'update' => 'updateBannerCategory',
            'index'  => 'indexBannerCategory',
            'delete' => 'deleteBannerCategory',
        ];

        /**
         * @param \yii\base\Action $action
         * @return bool
         */
        public function beforeAction($action)
        {
            if (parent::beforeAction($action)) {
                $this->model       = BannersCategory::className();
                $this->searchModel = BannersCategorySearch::className();

                return true;
            }

            return false;
        }
    }
