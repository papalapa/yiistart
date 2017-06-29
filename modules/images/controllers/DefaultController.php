<?php

    namespace papalapa\yiistart\modules\images\controllers;

    use papalapa\yiistart\controllers\MultilingualManageController;
    use papalapa\yiistart\modules\images\models\Images;
    use papalapa\yiistart\modules\images\models\ImagesSearch;
    use papalapa\yiistart\modules\users\models\BaseUser;

    /**
     * Class DefaultController
     * @package papalapa\yiistart\modules\images\controllers
     */
    class DefaultController extends MultilingualManageController
    {
        /**
         * @var array
         */
        protected $permissions = [
            'create' => 'createImage',
            'view'   => 'viewImage',
            'update' => 'updateImage',
            'index'  => 'indexImage',
            'delete' => 'deleteImage',
        ];

        /**
         * @param \yii\base\Action $action
         * @return bool
         */
        public function beforeAction($action)
        {
            if (parent::beforeAction($action)) {
                $this->model       = Images::className();
                $this->searchModel = ImagesSearch::className();

                if (\Yii::$app->user->identity->role == BaseUser::ROLE_DEVELOPER) {
                    $this->scenarios = array_replace($this->scenarios, [
                        'create' => Images::SCENARIO_DEVELOPER,
                        'update' => Images::SCENARIO_DEVELOPER,
                    ]);
                }

                return true;
            }

            return false;
        }
    }
