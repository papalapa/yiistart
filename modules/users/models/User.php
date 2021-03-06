<?php

    namespace papalapa\yiistart\modules\users\models;

    use yii\behaviors\TimestampBehavior;
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
        const SCENARIO_UPDATE      = 'update';
        const SCENARIO_SELF_DELETE = 'self-delete';

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
                    'value' => date('Y-m-d H:i:s'),
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
                [
                    ['role'], 'in',
                    'range' => !\Yii::$app->user->isGuest && \Yii::$app->user->identity->role === BaseUser::ROLE_DEVELOPER
                        ? [BaseUser::ROLE_USER, BaseUser::ROLE_AUTHOR, BaseUser::ROLE_MANAGER, BaseUser::ROLE_ADMIN, BaseUser::ROLE_DEVELOPER]
                        : [BaseUser::ROLE_USER, BaseUser::ROLE_AUTHOR, BaseUser::ROLE_MANAGER, BaseUser::ROLE_ADMIN],
                ],
                [['role'], 'default', 'value' => BaseUser::ROLE_USER],
            ];
        }

        /**
         * Prevent user deleting
         * @return bool
         */
        public function beforeDelete()
        {
            if (parent::beforeDelete()) {
                if ($this->scenario <> self::SCENARIO_SELF_DELETE) {
                    if (\Yii::$app->user->id === $this->id) {
                        \Yii::$app->session->addFlash('error', 'Нельзя удалить свою учётную запись.');

                        return false;
                    } elseif (\Yii::$app->user->identity->role < $this->role) {
                        \Yii::$app->session->addFlash('error', 'Нельзя удалить учётную запись пользователя с ролью выше вашей.');

                        return false;
                    }
                }

                return true;
            }

            return false;
        }
    }
