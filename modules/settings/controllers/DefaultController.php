<?php

    namespace backend\modules\settings\controllers;

    use backend\modules\settings\models\SettingsSearch;
    use common\ManageController;
    use common\models\Settings;

    /**
     * Class DefaultController
     * @package backend\modules\settings\controllers
     */
    class DefaultController extends ManageController
    {
        protected $permissions = [
            'create' => 'createSetting',
            'view'   => 'viewSetting',
            'update' => 'updateSetting',
            'index'  => 'indexSetting',
            'delete' => 'deleteSetting',
        ];

        public function init()
        {
            $this->model       = Settings::className();
            $this->searchModel = SettingsSearch::className();
            parent::init();
        }
    }
