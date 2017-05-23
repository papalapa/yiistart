<?php

    namespace papalapa\yiistart\modules\users\models;

    use yii\base\InvalidParamException;
    use yii\base\NotSupportedException;
    use yii\db\ActiveRecord;
    use yii\helpers\ArrayHelper;
    use yii\web\IdentityInterface;

    /**
     * Class BaseUser
     * @property integer $id
     * @property string  $email
     * @property string  $auth_key
     * @property string  $password_hash
     * @property string  $token
     * @property integer $status
     * @property integer $role
     * @property string  $password write-only password
     * @package papalapa\yiistart\modules\users\models
     */
    class BaseUser extends ActiveRecord implements IdentityInterface
    {
        const ROLE_GUEST     = 0;
        const ROLE_USER      = 1;
        const ROLE_AUTHOR    = 4;
        const ROLE_MANAGER   = 8;
        const ROLE_ADMIN     = 16;
        const ROLE_DEVELOPER = 32;
        const STATUS_READY   = 0;
        const STATUS_ACTIVE  = 1;
        const STATUS_DELETED = -1;

        /**
         * @return string
         */
        public static function tableName()
        {
            return '{{user}}';
        }

        /**
         * @return array
         */
        public static function statuses()
        {
            return [
                self::STATUS_READY   => 'ready',
                self::STATUS_ACTIVE  => 'active',
                self::STATUS_DELETED => 'deleted',
            ];
        }

        /**
         * @return array
         */
        public static function statusDescription()
        {
            return [
                self::STATUS_READY   => 'Зарегистрированный',
                self::STATUS_ACTIVE  => 'Активный',
                self::STATUS_DELETED => 'Удаленный',
            ];
        }

        /**
         * @return array
         */
        public static function roles()
        {
            return [
                self::ROLE_GUEST     => 'guest',
                self::ROLE_USER      => 'user',
                self::ROLE_AUTHOR    => 'author',
                self::ROLE_MANAGER   => 'manager',
                self::ROLE_ADMIN     => 'admin',
                self::ROLE_DEVELOPER => 'developer',
            ];
        }

        /**
         * @return array
         */
        public static function roleDescription()
        {
            return [
                self::ROLE_GUEST     => 'Гость',
                self::ROLE_USER      => 'Пользователь',
                self::ROLE_AUTHOR    => 'Автор',
                self::ROLE_MANAGER   => 'Менеджер',
                self::ROLE_ADMIN     => 'Администратор',
                self::ROLE_DEVELOPER => 'Разработчик',
            ];
        }

        /**
         * @inheritdoc
         */
        public static function findIdentity($id)
        {
            return static::findOne(['id' => $id]);
        }

        /**
         * @inheritdoc
         */
        public static function findIdentityByAccessToken($token, $type = null)
        {
            throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
        }

        /**
         * Finds user by email
         * @param string $email
         * @return static|null
         */
        public static function findByEmail($email)
        {
            return static::findOne(['email' => $email]);
        }

        /**
         * Finds user by token
         * @param $token
         * @return static|null
         */
        public static function findByToken($token)
        {
            if (!static::isTokenValid($token)) {
                return null;
            }

            return static::findOne(['token' => $token]);
        }

        /**
         * Finds out if password reset token is valid
         * @param string $token password reset token
         * @return boolean
         */
        public static function isTokenValid($token)
        {
            if (empty($token)) {
                return false;
            }

            $timestamp = (int)substr($token, strrpos($token, '_') + 1);
            $expire    = ArrayHelper::getValue(\Yii::$app->params, 'user.password.reset.token.expire', 3600);

            return $timestamp + $expire >= time();
        }

        /**
         * @param null $attribute
         * @return static|null|string
         */
        public static function identity($attribute = null)
        {
            /* @var $identity static */
            $identity = \Yii::$app->user->identity;

            if (!is_null($identity) && !is_null($attribute)) {
                if ($identity->hasAttribute($attribute)) {
                    return $identity->getAttribute($attribute);
                } else {
                    throw new InvalidParamException("User identity class ".__CLASS__." has not attribute named «{$attribute}»");
                }
            }

            return $identity;
        }

        /**
         * @inheritdoc
         */
        public function getId()
        {
            return $this->getPrimaryKey();
        }

        /**
         * @inheritdoc
         */
        public function validateAuthKey($authKey)
        {
            return $this->getAuthKey() === $authKey;
        }

        /**
         * @inheritdoc
         */
        public function getAuthKey()
        {
            return $this->auth_key;
        }

        /**
         * Validates password
         * @param string $password password to validate
         * @return boolean if password provided is valid for current user
         */
        public function validatePassword($password)
        {
            return \Yii::$app->security->validatePassword($password, $this->password_hash);
        }

        /**
         * Generates password hash from password and sets it to the model
         * @param string $password
         */
        public function setPassword($password)
        {
            $this->password_hash = \Yii::$app->security->generatePasswordHash($password);
        }

        /**
         * Generates "remember me" authentication key
         */
        public function generateAuthKey()
        {
            $this->auth_key = \Yii::$app->security->generateRandomString();
        }

        /**
         * Generates new password reset token
         */
        public function generateToken()
        {
            $this->token = \Yii::$app->security->generateRandomString().'_'.time();
        }

        /**
         * Removes password reset token
         */
        public function removeToken()
        {
            $this->token = null;
        }
    }
