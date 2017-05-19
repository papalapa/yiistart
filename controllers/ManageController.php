<?php

    namespace papalapa\yiistart\controllers;

    use yii\db\ActiveRecord;
    use yii\filters\AccessControl;
    use yii\filters\VerbFilter;
    use yii\helpers\Html;
    use yii\web\BadRequestHttpException;
    use yii\web\Controller;
    use yii\web\ForbiddenHttpException;
    use yii\web\NotFoundHttpException;

    /**
     * Class ManageController
     * @package papalapa\yiistart\controllers
     */
    abstract class ManageController extends Controller
    {
        /**
         * Default active record model class
         * @var ActiveRecord
         */
        protected $model;
        /**
         * Default active record search model class
         * @var ActiveRecord
         */
        protected $searchModel;
        /**
         * Permissions array
         * @var array
         */
        protected $permissions = [
            'create' => null,
            'view'   => null,
            'update' => null,
            'index'  => null,
            'delete' => null,
        ];

        /**
         * @inheritdoc
         */
        public function behaviors()
        {
            return [
                'verbs'  => [
                    'class'   => VerbFilter::className(),
                    'actions' => [
                        'index'  => ['get'],
                        'view'   => ['get'],
                        'create' => ['get', 'post'],
                        'update' => ['get', 'put', 'post'],
                        'delete' => ['post', 'delete'],
                    ],
                ],
                'access' => [
                    'class' => AccessControl::className(),
                    'only'  => ['index', 'view', 'create', 'update', 'delete'],
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['manager', 'admin', 'developer'],
                        ],
                        [
                            'allow'        => false,
                            'roles'        => [],
                            'denyCallback' => function ($rule, $action) {
                                $this->deniedError();
                            },
                        ],
                    ],
                ],
            ];
        }

        /**
         * Lists all models.
         * @return string
         */
        public function actionIndex()
        {
            /* @var $searchModel ActiveRecord */
            $searchModel  = new $this->searchModel;
            $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

            return $this->render('index', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
        }

        /**
         * Displays a single model.
         * @param $id
         * @return string
         * @throws ForbiddenHttpException
         * @throws NotFoundHttpException
         */
        public function actionView($id)
        {
            $model = $this->findModel($id);

            if (!$model->getAttribute('is_active') && !\Yii::$app->user->can($this->permissions['view'])) {
                throw new ForbiddenHttpException('У вас недостаточно прав на просмотр');
            }

            return $this->render('view', ['model' => $model]);
        }

        /**
         * Creates a new model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         * @return string|\yii\web\Response
         * @throws ForbiddenHttpException
         */
        public function actionCreate()
        {
            if (false === \Yii::$app->user->can($this->permissions['create'])) {
                throw new ForbiddenHttpException('У вас недостаточно прав на создание');
            }

            /* @var $model ActiveRecord */
            $model = new $this->model;

            if ($model->load(\Yii::$app->request->post()) && $model->save()) {
                \Yii::$app->session->setFlash('success', 'Объект успешно создан и сохранен!');

                return $this->redirect(['view', 'id' => $model->primaryKey]);
            } else {
                return $this->render('create', ['model' => $model]);
            }
        }

        /**
         * Updates an existing model.
         * If update is successful, the browser will be redirected to the 'view' page.
         * @param $id
         * @return string|\yii\web\Response
         * @throws ForbiddenHttpException
         * @throws NotFoundHttpException
         */
        public function actionUpdate($id)
        {
            if (!\Yii::$app->user->can($this->permissions['update'])) {
                throw new ForbiddenHttpException('У вас недостаточно прав на изменение');
            }

            /* @var $model ActiveRecord */
            $model = $this->findModel($id);

            if (!\Yii::$app->user->can('ownerAccess', $model) && !\Yii::$app->user->can('foreignAccess', $model)) {
                throw new ForbiddenHttpException('У вас недостаточно прав на изменение чужих записей');
            }

            if ($model->load(\Yii::$app->request->post()) && $model->save()) {
                \Yii::$app->session->setFlash('info', 'Изменения приняты!');

                return $this->redirect(['view', 'id' => $model->primaryKey]);
            } else {
                return $this->render('update', ['model' => $model]);
            }
        }

        /**
         * Deletes an existing model.
         * If deletion is successful, the browser will be redirected to the 'index' page.
         * @param $id
         * @return \yii\web\Response
         * @throws ForbiddenHttpException
         * @throws NotFoundHttpException
         * @throws \Exception
         */
        public function actionDelete($id)
        {
            if (!\Yii::$app->user->can($this->permissions['delete'])) {
                throw new ForbiddenHttpException('У вас недостаточно прав на удаление');
            }

            /* @var $model ActiveRecord */
            $model = $this->findModel($id);

            if (!\Yii::$app->user->can('ownerAccess', $model) && !\Yii::$app->user->can('foreignAccess', $model)) {
                throw new ForbiddenHttpException('У вас недостаточно прав на удаление чужих записей');
            }

            if ($model->delete()) {
                \Yii::$app->session->setFlash('success', 'Объект удален!');
            }

            return $this->redirect(['index']);
        }

        /**
         * @param      $id
         * @param      $attribute
         * @param null $value
         * @return string
         * @throws BadRequestHttpException
         */
        public function actionToggle($id, $attribute, $value = null)
        {
            if (!\Yii::$app->request->isAjax) {
                $this->redirect(['index'], [400 => 'Метод не поддерживается']);
            }

            try {
                $model = $this->findModel($id);
            } catch (NotFoundHttpException $e) {
                return Html::tag('i', null, ['class' => 'fa fa-ban text-danger', 'title' => 'Не найдено!']);
            }

            if (!\Yii::$app->user->can($this->permissions['update'])) {
                return Html::tag('i', null, ['class' => 'fa fa-ban text-danger', 'title' => 'Нет прав на изменение!']);
            }

            if (!\Yii::$app->user->can('ownerAccess', $model) && !\Yii::$app->user->can('foreignAccess', $model)) {
                return Html::tag('i', null, ['class' => 'fa fa-ban text-danger', 'title' => 'Нет прав на изменение!']);
            }

            if (!$model->hasAttribute($attribute)) {
                return Html::tag('i', null, ['class' => 'fa fa-ban text-danger', 'title' => 'Атрибут не найден!']);
            }

            if (!$model->isAttributeSafe($attribute)) {
                return Html::tag('i', null, ['class' => 'fa fa-ban text-danger', 'title' => 'Заданный атрибут не доступен для изменения!']);
            }

            if (is_null($value)) {
                if (is_null($model->getAttribute($attribute))) {
                    $model->setAttribute($attribute, false);
                } else {
                    $model->setAttribute($attribute, !$model->getAttribute($attribute));
                }
            } else {
                $model->setAttribute($attribute, (bool)$value);
            }

            if (!$model->save()) {
                $errors = $model->errors;

                return Html::tag('i', null, ['class' => 'fa fa-ban text-danger', 'title' => reset($errors)]);
            }

            \Yii::$app->session->setFlash('info', 'Изменения приняты!');

            return $this->renderAjax('@vendor/papalapa/yiistart/widgets/views/grid-toggle-column.php',
                ['model' => $model, 'attribute' => $attribute]);
        }

        /**
         * Not authorized user see this exception
         * @throws ForbiddenHttpException
         */
        protected function deniedError()
        {
            throw new ForbiddenHttpException('У вас недостаточно прав.');
        }

        /**
         * Finds the Content model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         * @param string $id
         * @return ActiveRecord the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        protected function findModel($id)
        {
            /* @var $activeRecord ActiveRecord */
            $activeRecord = new $this->model;

            if (null === $model = $activeRecord::findOne($id)) {
                throw new NotFoundHttpException('Указанная страница не найдена.');
            }

            return $model;
        }
    }
