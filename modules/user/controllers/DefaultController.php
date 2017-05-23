<?php

    namespace papalapa\yiistart\modules\users\controllers;

    use papalapa\yiistart\controllers\ManageController;
    use papalapa\yiistart\modules\users\models\User;
    use papalapa\yiistart\modules\users\models\UserSearch;

    /**
     * Class DefaultController
     * @package papalapa\yiistart\modules\users\controllers
     */
    class DefaultController extends ManageController
    {
        /**
         * @var array
         */
        protected $permissions = [
            'create' => 'createUser',
            'view'   => 'viewUser',
            'update' => 'updateUser',
            'index'  => 'indexUser',
            'delete' => 'deleteUser',
        ];

        /**
         * @inheritdoc
         */
        public function init()
        {
            $this->model       = User::className();
            $this->searchModel = UserSearch::className();
            parent::init();
        }
    }
