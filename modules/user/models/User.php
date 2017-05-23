<?php

    namespace papalapa\yiistart\modules\users\models;

    use yii\behaviors\TimestampBehavior;
    use yii\db\Expression;

    /**
     * Class User
     * @property integer $id
     * @property string  $email
     * @property string  $auth_key
     * @property string  $password_hash
     * @property string  $token
     * @property integer $status
     * @property integer $role
     * @property integer $last_ip
     * @property integer $created_at
     * @property integer $updated_at
     * @property integer $activity_at
     * @property string  $password write-only password
     * @package papalapa\yiistart\models
     */
    class User extends BaseUser
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return '{{user}}';
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'id'            => 'ID',
                'email'         => 'Email',
                'auth_key'      => 'Ключ авторизации',
                'password_hash' => 'Хеш пароля',
                'token'         => 'Токен',
                'status'        => 'Статус',
                'role'          => 'Роль',
                'last_ip'       => 'Последний IP',
                'created_at'    => 'Дата создания',
                'updated_at'    => 'Дата изменения',
                'activity_at'   => 'Дата активности',
            ];
        }

        /**
         * @inheritdoc
         */
        public function behaviors()
        {
            return [
                'TimestampBehavior' => [
                    'class' => TimestampBehavior::className(),
                    'value' => new Expression('NOW()'),
                ],
            ];
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                ['email', 'email'],
                ['email', 'unique'],

                [['status'], 'integer'],
                [['status'], 'in', 'range' => [BaseUser::STATUS_DELETED, BaseUser::STATUS_READY, BaseUser::STATUS_ACTIVE]],
                [['status'], 'default', 'value' => BaseUser::STATUS_READY],

                [['role'], 'integer', 'min' => 0],
                [['role'], 'in', 'range' => [BaseUser::ROLE_USER, BaseUser::ROLE_AUTHOR, BaseUser::ROLE_MANAGER, BaseUser::ROLE_ADMIN]],
                [['role'], 'default', 'value' => BaseUser::ROLE_USER],
            ];
        }

        /**
         * @param bool  $insert
         * @param array $changedAttributes
         */
        public function afterSave($insert, $changedAttributes)
        {
            if ($insert) {
                \Yii::$app->session->setFlash('success', 'Учетная запись зарегистрирована!');
                \Yii::$app->mailer
                    ->compose(['html' => 'signupConfirm-html', 'text' => 'signupConfirm-text'], ['user' => $this])
                    ->setFrom([\Yii::$app->params['noreply.email'] => \Yii::$app->name.' robot'])
                    ->setTo($this->email)
                    ->setSubject('Подтверждение email для '.\Yii::$app->name)
                    ->send();
            }

            parent::afterSave($insert, $changedAttributes);
        }

        /**
         * @return bool
         */
        public function beforeDelete()
        {
            if (parent::beforeDelete()) {
                if ($this->email == \Yii::$app->user->identity->email) {
                    \Yii::$app->session->setFlash('error', 'Невозможно удалить свою учетную запись!');

                    return false;
                }

                return true;
            }

            return false;
        }
    }
