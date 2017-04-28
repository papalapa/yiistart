<?php

    namespace backend\modules\user\controllers;

    use backend\modules\user\models\LoginForm;
    use backend\modules\user\models\PasswordResetForm;
    use backend\modules\user\models\RequestPasswordResetForm;
    use yii\base\InvalidParamException;
    use yii\filters\AccessControl;
    use yii\filters\VerbFilter;
    use yii\web\BadRequestHttpException;
    use yii\web\Controller;

    /**
     * Class IdentityController
     * @package backend\modules\user\controllers
     */
    class IdentityController extends Controller
    {
        /**
         * Simple layout
         * @var string
         */
        public $layout = '@app/views/layouts/main';

        /**
         * @return array
         */
        public function behaviors()
        {
            return [
                'verbs'  => [
                    'class'   => VerbFilter::className(),
                    'actions' => [
                        'logout' => ['post'],
                    ],
                ],
                'access' => [
                    'class' => AccessControl::className(),
                    'only'  => ['login', 'logout', 'request-password-reset', 'password-reset'],
                    'rules' => [
                        [
                            'allow'   => true,
                            'actions' => ['login', 'request-password-reset', 'password-reset'],
                            'roles'   => ['?'],
                        ],
                        [
                            'allow'   => true,
                            'actions' => ['logout'],
                            'roles'   => ['@'],
                        ],
                        [
                            'allow'        => false,
                            'actions'      => ['login', 'request-password-reset', 'password-reset'],
                            'roles'        => ['@'],
                            'denyCallback' => function ($rule, $action) {
                                return $this->redirect(['/site/index']);
                            },
                        ],
                        [
                            'allow'        => false,
                            'actions'      => ['logout'],
                            'roles'        => ['?'],
                            'denyCallback' => function ($rule, $action) {
                                return $this->redirect(['/user/identity/login']);
                            },
                        ],
                    ],
                ],
            ];
        }

        /**
         * Logs in a user.
         * @return mixed
         */
        public function actionLogin()
        {
            $model = new LoginForm();

            if ($model->load(\Yii::$app->request->post()) && $model->login()) {
                return $this->goBack();
            } else {
                return $this->render('login', [
                    'model' => $model,
                ]);
            }
        }

        /**
         * Logs out the current user.
         * @return mixed
         */
        public function actionLogout()
        {
            \Yii::$app->user->logout();

            return $this->goHome();
        }

        /**
         * Requests password reset.
         * @return mixed
         */
        public function actionRequestPasswordReset()
        {
            $model = new RequestPasswordResetForm();
            if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
                if ($model->sendEmail()) {
                    \Yii::$app->session->setFlash('success', 'Инструкция для сброса пароля отправлена вам на почту.');

                    return $this->goHome();
                } else {
                    \Yii::$app->session->setFlash('error', 'Мы просим прощения, но мы не можем сбросить ваш пароль в связи с ошибкой сервера.');
                }
            }

            return $this->render('requestPasswordReset', [
                'model' => $model,
            ]);
        }

        /**
         * Resets password.
         * @param string $token
         * @return mixed
         * @throws BadRequestHttpException
         */
        public function actionPasswordReset($token)
        {
            try {
                $model = new PasswordResetForm($token);
            } catch (InvalidParamException $e) {
                throw new BadRequestHttpException($e->getMessage());
            }

            if ($model->load(\Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
                \Yii::$app->session->setFlash('success', 'Новый пароль сохранен.');

                return $this->goHome();
            }

            return $this->render('passwordReset', [
                'model' => $model,
            ]);
        }
    }
