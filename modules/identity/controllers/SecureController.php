<?php

    namespace backend\modules\user\controllers;

    use backend\modules\user\models\SecureForm;
    use yii\filters\AccessControl;
    use yii\web\Controller;

    /**
     * Class SecureController
     * @package backend\modules\user\controllers
     */
    class SecureController extends Controller
    {
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
                            'roles' => ['@'],
                        ],
                    ],
                ],
            ];
        }

        /**
         * @return string
         */
        public function actionIndex()
        {
            $model = new SecureForm();

            if ($this->validateModel($model)) {
                return $this->refresh();
            }

            return $this->render('index', ['model' => $model]);
        }

        /**
         * @param SecureForm $model
         * @return bool
         */
        protected function validateModel(SecureForm $model)
        {
            if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
                if ($model->saveProfile()) {
                    \Yii::$app->session->setFlash('success', 'Профиль изменен.');

                    return true;
                } else {
                    \Yii::$app->session->setFlash('danger', 'Профиль не изменен.');
                }
            }

            return false;
        }
    }
