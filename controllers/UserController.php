<?php

    namespace vendor\papalapa\yiistart\controllers;

    use papalapa\yiistart\modules\users\models\BaseUser;
    use yii\base\DynamicModel;
    use yii\console\Controller;
    use yii\helpers\ArrayHelper;

    /**
     * Class UserController
     * @package vendor\papalapa\yiistart\controllers
     */
    class UserController extends Controller
    {
        /**
         * List all users
         */
        public function actionIndex()
        {
            /* @var $models BaseUser[] */
            $models = BaseUser::find()->orderBy(['id' => SORT_ASC])->all();
            foreach ($models as $model) {
                echo sprintf('%\'.-32.32s | %\'.10s | %\'.10s', $model->email, ArrayHelper::getValue(BaseUser::statuses(), $model->status),
                        ArrayHelper::getValue(BaseUser::roles(), $model->role)).PHP_EOL;
            }
        }

        /**
         * Creating user by email address, password and role
         * @param      $email
         * @param      $password
         * @param null $role
         */
        public function actionCreate($email, $password, $role = null)
        {
            $model = DynamicModel::validateData(compact(['email', 'password', 'role']), [
                ['email', 'filter', 'filter' => 'trim'],
                ['email', 'required'],
                ['email', 'email'],
                ['email', 'string', 'max' => 128],
                ['email', 'unique', 'targetClass' => BaseUser::className()],

                [['password'], 'required'],
                [['password'], 'string', 'min' => 6],

                [['role'], 'integer', 'min' => 0],
                [['role'], 'in', 'range' => [BaseUser::ROLE_USER, BaseUser::ROLE_AUTHOR, BaseUser::ROLE_MANAGER, BaseUser::ROLE_ADMIN, BaseUser::ROLE_DEVELOPER]],
                [['role'], 'default', 'value' => BaseUser::ROLE_ADMIN],
            ]);

            if ($model->hasErrors()) {
                $errors = $model->getFirstErrors();
                $error  = reset($errors);
                echo $error.PHP_EOL;
            } else {
                $user         = new BaseUser();
                $user->email  = $email;
                $user->status = BaseUser::STATUS_ACTIVE;
                $user->role   = $role;
                $user->generateAuthKey();
                $user->setPassword($password);
                $user->save(false);
                echo sprintf('User created and activated (role = %s)', $role).PHP_EOL;
            }
        }

        /**
         * Deleting an user
         * @param $email
         */
        public function actionDelete($email)
        {
            $model = DynamicModel::validateData(compact(['email']), [
                ['email', 'filter', 'filter' => 'trim'],
                ['email', 'required'],
                ['email', 'email'],
                ['email', 'exist', 'targetClass' => BaseUser::className()],
            ]);

            if ($model->hasErrors()) {
                $errors = $model->getFirstErrors();
                $error  = reset($errors);
                echo $error.PHP_EOL;
            } else {
                $user = BaseUser::findByEmail($email);
                $user->delete();
                echo 'User deleted'.PHP_EOL;
            }
        }

        /**
         * Changing user`s password
         * @param $email
         * @param $password
         */
        public function actionPassword($email, $password)
        {
            $model = DynamicModel::validateData(compact(['email', 'password']), [
                ['email', 'filter', 'filter' => 'trim'],
                ['email', 'required'],
                ['email', 'email'],
                ['email', 'exist', 'targetClass' => BaseUser::className(), 'message' => 'Указанный email в базе не найден.'.PHP_EOL],
                [['password'], 'required'],
                [['password'], 'string', 'min' => 6],
            ]);

            if ($model->hasErrors()) {
                $errors = $model->getFirstErrors();
                $error  = reset($errors);
                echo $error.PHP_EOL;
            } else {
                $user = BaseUser::findByEmail($email);
                $user->setPassword($password);
                $user->generateAuthKey();
                $user->save(false);
                echo 'Password changed'.PHP_EOL;
            }
        }

        /**
         * Changing user`s role
         * @param $email
         * @param $role
         */
        public function actionRole($email, $role)
        {
            $model = DynamicModel::validateData(compact(['email', 'role']), [
                ['email', 'filter', 'filter' => 'trim'],
                ['email', 'required'],
                ['email', 'email'],
                ['email', 'exist', 'targetClass' => BaseUser::className()],
                [['role'], 'required'],
                [['role'], 'in', 'range' => [BaseUser::ROLE_USER, BaseUser::ROLE_AUTHOR, BaseUser::ROLE_MANAGER, BaseUser::ROLE_ADMIN, BaseUser::ROLE_DEVELOPER]],
            ]);

            if ($model->hasErrors()) {
                $errors = $model->getFirstErrors();
                $error  = reset($errors);
                echo $error.PHP_EOL;
            } else {
                $user       = BaseUser::findByEmail($email);
                $user->role = $role;
                $user->save(false);
                echo 'Role changed'.PHP_EOL;
            }
        }

        /**
         * Changing user`s status
         * @param $email
         * @param $status
         */
        public function actionStatus($email, $status)
        {
            $model = DynamicModel::validateData(compact(['email', 'status']), [
                ['email', 'filter', 'filter' => 'trim'],
                ['email', 'required'],
                ['email', 'email'],
                ['email', 'exist', 'targetClass' => BaseUser::className()],
                [['status'], 'required'],
                [['status'], 'in', 'range' => [BaseUser::STATUS_ACTIVE, BaseUser::STATUS_READY, BaseUser::STATUS_DELETED]],
            ]);

            if ($model->hasErrors()) {
                $errors = $model->getFirstErrors();
                $error  = reset($errors);
                echo $error.PHP_EOL;
            } else {
                $user         = BaseUser::findByEmail($email);
                $user->status = $status;
                $user->generateAuthKey();
                $user->save(false);
                echo 'Status changed'.PHP_EOL;
            }
        }
    }
