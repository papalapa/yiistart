<?php

    namespace papalapa\yiistart\modules\subscribe\controllers;

    use yii\web\Controller;

    /**
     * Class DefaultController
     * @package papalapa\yiistart\modules\subscribe\controllers
     */
    class DefaultController extends Controller
    {
        /**
         * Renders the index view for the module
         * @return string
         */
        public function actionIndex()
        {
            return $this->render('index');
        }
    }
