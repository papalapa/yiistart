<?php

    namespace papalapa\yiistart\modules\social\controllers;

    use papalapa\yiistart\controllers\ManageController;
    use papalapa\yiistart\modules\social\models\Social;
    use papalapa\yiistart\modules\social\models\SocialSearch;

    /**
     * Class DefaultController
     * @package papalapa\yiistart\modules\social\controllers
     */
    class DefaultController extends ManageController
    {
        /**
         * @var array
         */
        protected $permissions = [
            'create' => 'createSocial',
            'view'   => 'viewSocial',
            'update' => 'updateSocial',
            'index'  => 'indexSocial',
            'delete' => 'deleteSocial',
        ];

        /**
         * @param \yii\base\Action $action
         * @return bool
         */
        public function beforeAction($action)
        {
            if (parent::beforeAction($action)) {
                $this->model       = Social::className();
                $this->searchModel = SocialSearch::className();

                return true;
            }

            return false;
        }
    }
