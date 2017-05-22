<?php

    namespace papalapa\yiistart\modules\settings\controllers;

    use papalapa\yiistart\controllers\MultilingualManageController;
    use papalapa\yiistart\modules\settings\models\Settings;
    use papalapa\yiistart\modules\settings\models\SettingsSearch;

    /**
     * Class DefaultController
     * @package papalapa\yiistart\modules\settings\controllers
     */
    class DefaultController extends MultilingualManageController
    {
        /**
         * @var array
         */
        protected $permissions = [
            'create' => 'createSetting',
            'view'   => 'viewSetting',
            'update' => 'updateSetting',
            'index'  => 'indexSetting',
            'delete' => 'deleteSetting',
        ];

        /**
         * @inheritdoc
         */
        public function init()
        {
            $this->model       = Settings::className();
            $this->searchModel = SettingsSearch::className();

            parent::init();
        }
    }
