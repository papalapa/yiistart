<?php

    namespace papalapa\yiistart\controllers;

    use papalapa\yiistart\models\BelongingTags;
    use yii\filters\AccessControl;
    use yii\filters\VerbFilter;
    use yii\web\Controller;
    use yii\web\Response;

    /**
     * Class TagsAjaxController
     * To use this controller edit main.config like this:
     * ```php
     *      'controllerMap' => [
     *          'tags-ajax'    => [
     *              'class' => papalapa\yiistart\controllers\TagsAjaxController::className(),
     *          ],
     *      ]
     * ```
     * And then use papalapa\yiistart\widgets\MultiTags::widget()
     * @package papalapa\yiistart\controllers
     */
    class TagsAjaxController extends Controller
    {
        /**
         * @inheritdoc
         */
        public function behaviors()
        {
            return [
                'verbs'  => [
                    'class'   => VerbFilter::className(),
                    'actions' => [
                        'index' => ['get', 'post', 'ajax'],
                    ],
                ],
                'access' => [
                    'class' => AccessControl::className(),
                    'only'  => ['index'],
                    'rules' => [
                        [
                            'actions' => ['index'],
                            'allow'   => true,
                            'roles'   => ['manager', 'admin', 'developer'],
                        ],
                    ],
                ],
            ];
        }

        /**
         * @param null $tag
         * @param null $type
         * @return array
         */
        public function actionIndex($tag = null, $type = null)
        {
            \Yii::$app->response->format = Response::FORMAT_JSON;

            if (isset($tag)) {
                $data = BelongingTags::find()
                    ->select(['id' => 'LOWER([[tag]])', 'text' => 'LOWER([[tag]])'], 'DISTINCT')
                    ->andFilterWhere(['content_type' => $type])
                    ->andFilterWhere(['like', 'tag', $tag])
                    ->orderBy(['tag' => SORT_ASC])
                    ->limit(10)
                    ->asArray()
                    ->all();
            }

            return ['items' => isset($data) ? $data : []];
        }
    }
