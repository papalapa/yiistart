<?php

    namespace papalapa\yiistart\modules\elements\controllers;

    use papalapa\yiistart\controllers\MultilingualManageController;
    use papalapa\yiistart\modules\elements\models\Elements;
    use papalapa\yiistart\modules\elements\models\ElementsSearch;

    /**
     * Class DefaultController
     * @package papalapa\yiistart\modules\elements\controllers
     */
    class DefaultController extends MultilingualManageController
    {
        /**
         * @var array
         */
        protected $permissions = [
            'create' => 'createElement',
            'view'   => 'viewElement',
            'update' => 'updateElement',
            'index'  => 'indexElement',
            'delete' => 'deleteElement',
        ];

        /**
         * @inheritdoc
         */
        public function init()
        {
            $this->model       = Elements::className();
            $this->searchModel = ElementsSearch::className();

            parent::init();
        }
    }
