<?php

    namespace vendor\papalapa\yiistart\controllers;

    use papalapa\yiistart\modules\users\models\BaseUser;
    use papalapa\yiistart\modules\users\models\User;
    use yii\base\DynamicModel;
    use yii\console\Controller;

    /**
     * Class UserController
     * @package vendor\papalapa\yiistart\controllers
     */
    class UserController extends Controller
    {
        /**
         * @param      $email
         * @param      $password
         * @param null $status
         * @param null $role
         */
        public function actionCreate($email, $password, $status = null, $role = null)
        {
            $model = DynamicModel::validateData(compact(['email', 'password', 'status', 'role']), [
                ['email', 'filter', 'filter' => 'trim'],
                ['email', 'required'],
                ['email', 'email'],
                ['email', 'string', 'max' => 128],
                ['email', 'unique', 'targetClass' => BaseUser::className()],

                [['password'], 'required'],
                [['password'], 'string', 'min' => 6],

                [['status'], 'integer'],
                [['status'], 'in', 'range' => [BaseUser::STATUS_DELETED, BaseUser::STATUS_READY, BaseUser::STATUS_ACTIVE]],
                [['status'], 'default', 'value' => BaseUser::STATUS_ACTIVE],

                [['role'], 'integer', 'min' => 0],
                [['role'], 'in', 'range' => [BaseUser::ROLE_USER, BaseUser::ROLE_AUTHOR, BaseUser::ROLE_MANAGER, BaseUser::ROLE_ADMIN, BaseUser::ROLE_DEVELOPER]],
                [['role'], 'default', 'value' => BaseUser::ROLE_ADMIN],
            ]);

            if ($model->hasErrors()) {
                $errors = $model->getFirstErrors();
                $error  = reset($errors);
                echo $error.PHP_EOL;
            } else {
                $user         = new User();
                $user->email  = $email;
                $user->status = $status;
                $user->role   = $role;
                $user->generateAuthKey();
                $user->generateToken();
                $user->setPassword($password);
                $user->save(false);
                echo sprintf('User created and activated (role = %s)', $role).PHP_EOL;
            }
        }

        /**
         * @param $email
         * @param $password
         */
        public function actionPassword($email, $password)
        {
            $model = DynamicModel::validateData(compact(['email', 'password']), [
                ['email', 'filter', 'filter' => 'trim'],
                ['email', 'required'],
                ['email', 'email'],
                [
                    'email',
                    'exist',
                    'targetClass' => User::className(),
                    'message'     => 'Указанный email в базе не найден.'.PHP_EOL,
                ],
                [['password'], 'required'],
                [['password'], 'string', 'min' => 6],
            ]);

            if ($model->hasErrors()) {
                $errors = $model->getFirstErrors();
                $error  = reset($errors);
                echo $error.PHP_EOL;
            } else {
                $user = User::findByEmail($email);
                $user->setPassword($password);
                $user->save(false);
                echo 'Password changed'.PHP_EOL;
            }
        }

        /**
         * @param $email
         * @param $role
         */
        public function actionRole($email, $role)
        {
            $model = DynamicModel::validateData(compact(['email', 'role']), [
                ['email', 'filter', 'filter' => 'trim'],
                ['email', 'required'],
                ['email', 'email'],
                ['email', 'exist', 'targetClass' => User::className()],
                [['role'], 'required'],
                [['role'], 'in', 'range' => [BaseUser::ROLE_USER, BaseUser::ROLE_AUTHOR, BaseUser::ROLE_MANAGER, BaseUser::ROLE_ADMIN, BaseUser::ROLE_DEVELOPER]],
            ]);

            if ($model->hasErrors()) {
                $errors = $model->getFirstErrors();
                $error  = reset($errors);
                echo $error.PHP_EOL;
            } else {
                $user       = User::findByEmail($email);
                $user->role = $role;
                $user->save(false);
                echo 'Role changed'.PHP_EOL;
            }
        }

        /**
         * @param $email
         * @param $status
         */
        public function actionStatus($email, $status)
        {
            $model = DynamicModel::validateData(compact(['email', 'status']), [
                ['email', 'filter', 'filter' => 'trim'],
                ['email', 'required'],
                ['email', 'email'],
                ['email', 'exist', 'targetClass' => User::className()],
                [['status'], 'required'],
                [['status'], 'in', 'range' => [BaseUser::STATUS_ACTIVE, BaseUser::STATUS_READY, BaseUser::STATUS_DELETED]],
            ]);

            if ($model->hasErrors()) {
                $errors = $model->getFirstErrors();
                $error  = reset($errors);
                echo $error.PHP_EOL;
            } else {
                $user         = User::findByEmail($email);
                $user->status = $status;
                $user->save(false);
                echo 'Status changed'.PHP_EOL;
            }
        }
    }
