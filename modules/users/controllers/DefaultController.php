<?php

    namespace papalapa\yiistart\modules\users\controllers;

    use common\modules\user\models\User;
    use papalapa\yiistart\controllers\ManageController;
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
         * @param \yii\base\Action $action
         * @return bool
         */
        public function beforeAction($action)
        {
            if (parent::beforeAction($action)) {
                $this->model       = User::className();
                $this->searchModel = UserSearch::className();

                $this->scenarios = array_replace($this->scenarios, [
                    'update' => User::SCENARIO_UPDATE,
                ]);

                return true;
            }

            return false;
        }
    }
