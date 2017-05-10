<?php

    namespace papalapa\yiistart\modules\elements\controllers;

    use papalapa\yiistart\controllers\ManageController;
    use papalapa\yiistart\modules\elements\models\ElementCategory;
    use papalapa\yiistart\modules\elements\models\ElementCategorySearch;

    /**
     * Class CategoryController
     * @package papalapa\yiistart\modules\elements\controllers
     */
    class CategoryController extends ManageController
    {
        /**
         * @var array
         */
        protected $permissions = [
            'create' => 'createElementCategory',
            'view'   => 'viewElementCategory',
            'update' => 'updateElementCategory',
            'index'  => 'indexElementCategory',
            'delete' => 'deleteElementCategory',
        ];

        /**
         * @inheritdoc
         */
        public function init()
        {
            $this->model       = ElementCategory::className();
            $this->searchModel = ElementCategorySearch::className();

            parent::init();
        }
    }
