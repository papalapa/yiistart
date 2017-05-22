<?php

    namespace papalapa\yiistart\modules\subscribe\controllers;

    use papalapa\yiistart\controllers\ManageController;
    use papalapa\yiistart\modules\subscribe\models\Dispatches;
    use papalapa\yiistart\modules\subscribe\models\DispatchesSearch;

    /**
     * Class DispatchesController
     * @package papalapa\yiistart\modules\subscribe\controllers
     */
    class DispatchesController extends ManageController
    {
        /**
         * @var array
         */
        protected $permissions = [
            'create' => 'createDispatch',
            'view'   => 'viewDispatch',
            'update' => 'updateDispatch',
            'index'  => 'indexDispatch',
            'delete' => 'deleteDispatch',
        ];

        /**
         * @inheritdoc
         */
        public function init()
        {
            $this->model       = Dispatches::className();
            $this->searchModel = DispatchesSearch::className();

            parent::init();
        }
    }
