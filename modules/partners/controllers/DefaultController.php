<?php

    namespace papalapa\yiistart\modules\partners\controllers;

    use papalapa\yiistart\controllers\MultilingualManageController;
    use papalapa\yiistart\modules\partners\models\Partners;
    use papalapa\yiistart\modules\partners\models\PartnersSearch;

    /**
     * Class DefaultController
     * @package papalapa\yiistart\modules\partners\controllers
     */
    class DefaultController extends MultilingualManageController
    {
        /**
         * @var array
         */
        protected $permissions = [
            'create' => 'createPartner',
            'view'   => 'viewPartner',
            'update' => 'updatePartner',
            'index'  => 'indexPartner',
            'delete' => 'deletePartner',
        ];

        /**
         * @param \yii\base\Action $action
         * @return bool
         */
        public function beforeAction($action)
        {
            if (parent::beforeAction($action)) {
                $this->model       = Partners::className();
                $this->searchModel = PartnersSearch::className();

                return true;
            }

            return false;
        }
    }
