<?php

    namespace papalapa\yiistart\modules\i18n\controllers;

    use papalapa\yiistart\controllers\ManageController;
    use papalapa\yiistart\modules\i18n\models\SourceMessage;
    use papalapa\yiistart\modules\i18n\models\SourceMessageSearch;
    use yii\base\Model;
    use yii\web\ForbiddenHttpException;
    use yii\web\NotFoundHttpException;

    /**
     * Class DefaultController
     * @package papalapa\yiistart\modules\i18n\controllers
     */
    class DefaultController extends ManageController
    {
        /**
         * @var array
         */
        protected $permissions = [
            'create' => 'createTranslation',
            'view'   => 'viewTranslation',
            'update' => 'updateTranslation',
            'index'  => 'indexTranslation',
            'delete' => 'deleteTranslation',
        ];

        /**
         * @inheritdoc
         */
        public function init()
        {
            $this->model       = SourceMessage::className();
            $this->searchModel = SourceMessageSearch::className();
            parent::init();
        }

        /**
         * @param $id
         * @return string
         * @throws ForbiddenHttpException
         */
        public function actionUpdate($id)
        {
            /** @var $model SourceMessage */
            $model = $this->findModel($id);

            if (!\Yii::$app->user->can($this->permissions['update'])) {
                throw new ForbiddenHttpException('Вам не разрешено данное действие');
            }

            if (!\Yii::$app->user->can('ownerAccess', $model) && !\Yii::$app->user->can('foreignAccess', $model)) {
                throw new ForbiddenHttpException('У вас нет прав на изменение данного контента');
            }

            $model->initMessages();

            if (Model::loadMultiple($model->messages, \Yii::$app->getRequest()->post()) && Model::validateMultiple($model->messages)) {

                $model->saveMessages();
                \Yii::$app->session->setFlash('success', 'Изменено успешно!');

                return $this->render('update', ['model' => $model]);
            } else {
                if ($model->hasErrors()) {
                    \Yii::$app->session->setFlash('error', 'Исправьте ошибки!');
                }

                return $this->render('update', ['model' => $model]);
            }
        }

        /**
         * @param string $id
         * @return array|null|\yii\db\ActiveRecord|\yii\db\ActiveRecord[]
         * @throws NotFoundHttpException
         */
        protected function findModel($id)
        {
            $query  = SourceMessage::find()->where('id = :id', [':id' => $id]);
            $models = is_array($id) ? $query->all() : $query->one();

            if (empty($models)) {
                throw new NotFoundHttpException('Указанная страница не найдена.');
            }

            return $models;
        }
    }
