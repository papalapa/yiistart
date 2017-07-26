<?php

    namespace papalapa\yiistart\modules\i18n\controllers;

    use papalapa\yiistart\controllers\ManageController;
    use papalapa\yiistart\modules\i18n\models\SourceMessageCategories;
    use papalapa\yiistart\modules\i18n\models\SourceMessageCategoriesSearch;

    /**
     * Class CategoriesController
     * @package papalapa\yiistart\modules\i18n\controllers
     */
    class CategoriesController extends ManageController
    {
        /**
         * @var array
         */
        protected $permissions = [
            'create' => 'createSourceMessageCategory',
            'view'   => 'viewSourceMessageCategory',
            'update' => 'updateSourceMessageCategory',
            'index'  => 'indexSourceMessageCategory',
            'delete' => 'deleteSourceMessageCategory',
        ];

        /**
         * @param \yii\base\Action $action
         * @return bool
         */
        public function beforeAction($action)
        {
            if (parent::beforeAction($action)) {
                $this->model       = SourceMessageCategories::className();
                $this->searchModel = SourceMessageCategoriesSearch::className();

                return true;
            }

            return false;
        }
    }
