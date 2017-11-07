<?php

    namespace papalapa\yiistart\modules\menu\controllers;

    use papalapa\yiistart\controllers\MultilingualManageController;
    use papalapa\yiistart\modules\menu\models\Menu;
    use papalapa\yiistart\modules\menu\models\MenuSearch;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Json;

    /**
     * Class DefaultController
     * @package papalapa\yiistart\modules\menu\controllers
     */
    class DefaultController extends MultilingualManageController
    {
        /**
         * @var array
         */
        protected $permissions = [
            'create' => 'createMenu',
            'view'   => 'viewMenu',
            'update' => 'updateMenu',
            'index'  => 'indexMenu',
            'delete' => 'deleteMenu',
        ];

        /**
         * @param \yii\base\Action $action
         * @return bool
         */
        public function beforeAction($action)
        {
            if (parent::beforeAction($action)) {
                $this->model       = Menu::className();
                $this->searchModel = MenuSearch::className();

                return true;
            }

            return false;
        }

        /**
         * Returns parents of position
         * @see http://demos.krajee.com/widget-details/depdrop
         * @param null $id
         * @return string
         */
        public function actionPositionParents($id = null)
        {
            $output          = [];
            $depdrop_parents = ArrayHelper::getValue(\Yii::$app->request->post(), 'depdrop_parents');

            if ($position = ArrayHelper::getValue($depdrop_parents, 0)) {
                $items = Menu::find()
                             ->andFilterWhere(['<>', 'id', $id])
                             ->andWhere(['position' => $position])
                             ->andWhere(['<', 'level', Menu::maxLevelOf($position)])
                             ->orderBy(['parent_id' => SORT_ASC, 'level' => SORT_ASC, 'name' => SORT_ASC])
                             ->all();

                foreach ($items as $item) {
                    $output[] = ['id' => $item->id, 'name' => sprintf('%s %s', str_repeat('—', $item->level), $item->name)];
                }
            }

            return Json::encode(['output' => $output, 'selected' => '']);
        }
    }
