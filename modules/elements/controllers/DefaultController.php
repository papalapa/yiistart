<?php

    namespace papalapa\yiistart\modules\elements\controllers;

    use papalapa\yiistart\controllers\MultilingualManageController;
    use papalapa\yiistart\modules\elements\models\Elements;
    use papalapa\yiistart\modules\elements\models\ElementsSearch;
    use papalapa\yiistart\modules\users\models\BaseUser;

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
         * @param \yii\base\Action $action
         * @return bool
         */
        public function beforeAction($action)
        {
            if (parent::beforeAction($action)) {
                $this->model       = Elements::className();
                $this->searchModel = ElementsSearch::className();

                if (\Yii::$app->user->identity->role == BaseUser::ROLE_DEVELOPER) {
                    $this->scenarios = array_replace($this->scenarios, [
                        'create' => Elements::SCENARIO_DEVELOPER,
                        'update' => Elements::SCENARIO_DEVELOPER,
                    ]);
                }

                return true;
            }

            return false;
        }
    }
