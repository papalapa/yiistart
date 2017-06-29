<?php

    namespace papalapa\yiistart\modules\pages\controllers;

    use papalapa\yiistart\controllers\MultilingualManageController;
    use papalapa\yiistart\modules\pages\models\Pages;
    use papalapa\yiistart\modules\pages\models\PagesSearch;
    use papalapa\yiistart\modules\users\models\BaseUser;

    /**
     * Class DefaultController
     * @package papalapa\yiistart\modules\pages\controllers
     */
    class DefaultController extends MultilingualManageController
    {
        /**
         * @var array
         */
        protected $permissions = [
            'create' => 'createPage',
            'view'   => 'viewPage',
            'update' => 'updatePage',
            'index'  => 'indexPage',
            'delete' => 'deletePage',
        ];

        /**
         * @param \yii\base\Action $action
         * @return bool
         */
        public function beforeAction($action)
        {
            if (parent::beforeAction($action)) {
                $this->model       = Pages::className();
                $this->searchModel = PagesSearch::className();

                if (\Yii::$app->user->identity->role == BaseUser::ROLE_DEVELOPER) {
                    $this->scenarios = array_replace($this->scenarios, [
                        'create' => Pages::SCENARIO_DEVELOPER,
                        'update' => Pages::SCENARIO_DEVELOPER,
                    ]);
                }

                return true;
            }

            return false;
        }
    }
