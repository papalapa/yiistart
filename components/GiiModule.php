<?php

    namespace papalapa\yiistart\components;

    use papalapa\yiistart\modules\users\models\BaseUser;
    use yii\gii\Module;
    use yii\web\ForbiddenHttpException;

    /**
     * Class GiiModule
     * @package papalapa\yiistart\components
     */
    class GiiModule extends Module
    {
        /**
         * @var array
         */
        public $generators = [
            'crud' => [
                'class'               => 'yii\gii\generators\crud\Generator',
                'baseControllerClass' => 'papalapa\yiistart\controllers\ManageController',
                'templates'           => [
                    'manage'              => '@vendor/papalapa/yiistart/widgets/gii/manage',
                    'manage-multilingual' => '@vendor/papalapa/yiistart/widgets/gii/manage-multilingual',
                ],
            ],
        ];

        /**
         * @return bool
         * @throws \yii\web\ForbiddenHttpException
         */
        protected function checkAccess()
        {
            if (parent::checkAccess()) {
                if (\Yii::$app->user->isGuest || \Yii::$app->user->identity->role !== BaseUser::ROLE_DEVELOPER) {
                    throw new ForbiddenHttpException();
                }

                return true;
            }

            return false;
        }
    }
