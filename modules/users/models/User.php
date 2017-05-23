<?php

    namespace papalapa\yiistart\modules\users\models;

    use yii\behaviors\TimestampBehavior;
    use yii\db\Expression;
    use yii\helpers\ArrayHelper;

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
     * @package papalapa\yiistart\modules/users/models/
     */
    class User extends BaseUser
    {
        const SCENARIO_UPDATE = 'update';

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
         * @return array
         */
        public function scenarios()
        {
            return ArrayHelper::merge(parent::scenarios(), [
                self::SCENARIO_UPDATE => ['status', 'role'],
            ]);
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                ['email', 'trim'],
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
    }
