<?php

    namespace papalapa\yiistart\modules\image\controllers;

    use papalapa\yiistart\controllers\MultilingualManageController;
    use papalapa\yiistart\modules\image\models\Image;
    use papalapa\yiistart\modules\image\models\ImageSearch;
    use papalapa\yiistart\modules\users\models\BaseUser;

    /**
     * Class DefaultController
     * DefaultController implements the CRUD actions for Image model.
     * @package papalapa\yiistart\modules\image\controllers
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
                $this->model       = Image::className();
                $this->searchModel = ImageSearch::className();

                if (\Yii::$app->user->identity->role == BaseUser::ROLE_DEVELOPER) {
                    $this->scenarios = array_replace($this->scenarios, [
                        'create' => Image::SCENARIO_DEVELOPER,
                        'update' => Image::SCENARIO_DEVELOPER,
                    ]);
                }

                return true;
            }

            return false;
        }
    }
