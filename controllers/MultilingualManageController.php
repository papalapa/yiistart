<?php

    namespace papalapa\yiistart\controllers;

    use papalapa\yiistart\models\MultilingualActiveRecord;
    use yii\web\NotFoundHttpException;

    /**
     * Class MultilingualManageController
     * @package papalapa\yiistart\controllers
     */
    abstract class MultilingualManageController extends ManageController
    {
        /**
         * Finds the Content model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         * @param string $id
         * @return mixed
         * @throws NotFoundHttpException
         */
        protected function findModel($id)
        {
            /* @var $activeRecord MultilingualActiveRecord */
            $activeRecord = new $this->model;

            $model = $activeRecord::find()->multilingual()->where(['id' => $id])->one();

            if (null === $model) {
                throw new NotFoundHttpException('Указанная страница не найдена.');
            }

            return $model;
        }
    }
