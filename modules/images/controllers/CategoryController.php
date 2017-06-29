<?php

    namespace papalapa\yiistart\modules\images\controllers;

    use papalapa\yiistart\controllers\ManageController;
    use papalapa\yiistart\modules\images\models\ImageCategory;
    use papalapa\yiistart\modules\images\models\ImageCategorySearch;

    /**
     * Class CategoryController
     * @package papalapa\yiistart\modules\images\controllers
     */
    class CategoryController extends ManageController
    {
        /**
         * @var array
         */
        protected $permissions = [
            'create' => 'createImageCategory',
            'view'   => 'viewImageCategory',
            'update' => 'updateImageCategory',
            'index'  => 'indexImageCategory',
            'delete' => 'deleteImageCategory',
        ];

        /**
         * @param \yii\base\Action $action
         * @return bool
         */
        public function beforeAction($action)
        {
            if (parent::beforeAction($action)) {
                $this->model       = ImageCategory::className();
                $this->searchModel = ImageCategorySearch::className();

                return true;
            }

            return false;
        }
    }
