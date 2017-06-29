<?php

    namespace papalapa\yiistart\controllers;

    use yii\filters\AccessControl;
    use yii\web\Controller;
    use yii\web\ForbiddenHttpException;

    /**
     * Class AccessController
     * @package papalapa\yiistart\controllers
     */
    class AccessController extends Controller
    {
        /**
         * @var array
         */
        protected $roles = ['manager', 'admin', 'developer'];

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
                            'allow' => true,
                            'roles' => $this->roles,
                        ],
                        [
                            'allow'         => true,
                            'roles'         => ['@'],
                            'matchCallback' => function ($rule, $action) {
                                if (!\Yii::$app->user->isGuest) {
                                    \Yii::$app->user->logout();
                                }

                                throw new ForbiddenHttpException('Недостаточно прав!');
                            },
                        ],
                    ],
                ],
            ];
        }
    }
