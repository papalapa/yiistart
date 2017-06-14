<?php

    namespace papalapa\yiistart\modules\users\controllers;

    use common\modules\user\models\User;
    use papalapa\yiistart\controllers\ManageController;
    use papalapa\yiistart\modules\users\models\UserSearch;
    use yii\base\Model;

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
         * @var array
         */
        protected $scenarios = [
            'create' => Model::SCENARIO_DEFAULT,
            'view'   => Model::SCENARIO_DEFAULT,
            'update' => User::SCENARIO_UPDATE,
            'index'  => Model::SCENARIO_DEFAULT,
            'delete' => Model::SCENARIO_DEFAULT,
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
