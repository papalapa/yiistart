<?php

    namespace papalapa\yiistart\modules\identity\models;

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
     * @property integer $created_at
     * @property integer $updated_at
     * @property integer $activity_at
     * @property integer $last_ip
     * @property string  $name
     * @property string  $phone
     * @property string  $mobile
     * @property string  $bdate
     * @property integer $gender
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
                'created_at'    => 'Дата создания',
                'updated_at'    => 'Дата изменения',
                'activity_at'   => 'Активность',
                'last_ip'       => 'Последний IP',
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

                [['status'], 'integer'],
                [['status'], 'in', 'range' => [self::STATUS_DELETED, self::STATUS_READY, self::STATUS_ACTIVE]],
                [['status'], 'default', 'value' => self::STATUS_READY],

                [['role'], 'integer', 'min' => 0],
                [['role'], 'in', 'range' => [self::ROLE_USER, self::ROLE_AUTHOR, self::ROLE_MANAGER, self::ROLE_ADMIN]],
                [['role'], 'default', 'value' => self::ROLE_USER],
            ];
        }
    }
