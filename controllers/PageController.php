<?php

    namespace papalapa\yiistart\controllers;

    use papalapa\yiistart\modules\pages\models\Pages;
    use yii\web\Controller;
    use yii\web\NotFoundHttpException;

    /**
     * Class PageController
     * Using this controller will setting up title and meta tags or throwing NotFoundHttpException
     * Just use findPage() method like this:
     * ```php
     *      public function actionNews(){
     *          $model = $this->findPage([$this->id, $this->action->id]);
     *          return $this->render('news', ['model' => $model]);
     *      }
     * ```
     * Or throwing error:
     * ```php
     *      public function actionPage($id){
     *          $model = $this->findPage($id, true, 'This page not found on our site!');
     *          return $this->render('page', ['model' => $model]);
     *      }
     * ```
     * @package papalapa\yiistart\controllers
     */
    class PageController extends Controller
    {
        /**
         * @param string|integer|array $alias
         * @param bool                 $strict
         * @param string|null          $error
         * @return \papalapa\yiistart\modules\pages\models\Pages
         * @throws \yii\web\NotFoundHttpException
         */
        protected function findPage($alias, $strict = false, $error = 'Страница не найдена')
        {
            /* @var $model Pages */
            $model = Pages::pageOf($alias);

            if ($strict) {
                if (is_null($model) || !$model->is_active) {
                    throw new NotFoundHttpException($error);
                }
            } else {
                if (is_null($model) || !$model->is_active) {
                    return null;
                }
            }

            $this->view->title = $model->title;
            $this->view->registerMetaTag(['name' => 'description', 'content' => $model->description]);
            $this->view->registerMetaTag(['name' => 'keywords', 'content' => $model->keywords]);

            return $model;
        }
    }
