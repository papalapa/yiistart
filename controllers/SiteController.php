<?php

    namespace papalapa\yiistart\controllers;

    use yii\filters\AccessControl;
    use yii\web\Controller;
    use yii\web\ForbiddenHttpException;

    /**
     * Class SiteController
     * Default backend site controller to render user rights
     * @package papalapa\yiistart\controllers
     */
    class SiteController extends Controller
    {
        /**
         * @inheritdoc
         */
        public function actions()
        {
            return [
                'error' => [
                    'class' => 'yii\web\ErrorAction',
                    'view'  => '@vendor/papalapa/yiistart/widgets/views/error',
                ],
            ];
        }

        /**
         * @inheritdoc
         */
        public function behaviors()
        {
            return [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'actions' => ['error'],
                            'allow'   => true,
                        ],
                        [
                            'actions' => ['manager'],
                            'allow'   => true,
                            'roles'   => ['developer'],
                        ],
                        [
                            'actions' => ['index'],
                            'allow'   => true,
                            'roles'   => ['manager', 'admin', 'developer'],
                        ],
                        [
                            'allow'         => true,
                            'roles'         => ['@'],
                            'matchCallback' => function ($rule, $action) {
                                if (!\Yii::$app->user->isGuest) {
                                    \Yii::$app->user->logout();
                                }

                                throw new ForbiddenHttpException();
                            },
                        ],
                    ],
                ],
            ];
        }

        /**
         * Displays homepage.
         * @return string
         */
        public function actionIndex()
        {
            return $this->render('@vendor/papalapa/yiistart/widgets/views/index');
        }

        /**
         * @return string
         */
        public function actionManager()
        {
            return $this->render('@vendor/papalapa/yiistart/widgets/views/manager');
        }
    }
