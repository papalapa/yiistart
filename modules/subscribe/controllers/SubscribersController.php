<?php

    namespace papalapa\yiistart\modules\subscribe\controllers;

    use papalapa\yiistart\controllers\ManageController;
    use papalapa\yiistart\modules\subscribe\models\Subscribers;
    use papalapa\yiistart\modules\subscribe\models\SubscribersSearch;

    /**
     * Class SubscribersController
     * @package papalapa\yiistart\modules\subscribe\controllers
     */
    class SubscribersController extends ManageController
    {
        /**
         * @var array
         */
        protected $permissions = [
            'create' => 'createSubscriber',
            'view'   => 'viewSubscriber',
            'update' => 'updateSubscriber',
            'index'  => 'indexSubscriber',
            'delete' => 'deleteSubscriber',
        ];

        /**
         * @inheritdoc
         */
        public function init()
        {
            $this->model       = Subscribers::className();
            $this->searchModel = SubscribersSearch::className();

            parent::init();
        }
    }
