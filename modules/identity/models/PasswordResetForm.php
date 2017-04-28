<?php

    namespace backend\modules\user\models;

    use common\models\User;
    use yii\base\InvalidParamException;
    use yii\base\Model;

    /**
     * Class ResetPasswordForm
     * @package backend\modules\user\models
     */
    class PasswordResetForm extends Model
    {
        public $password;
        /**
         * @var $_user User
         */
        private $_user;

        /**
         * Creates a form model given a token.
         * @param  string $token
         * @param  array  $config name-value pairs that will be used to initialize the object properties
         * @throws \yii\base\InvalidParamException if token is empty or not valid
         */
        public function __construct($token, $config = [])
        {
            if (empty($token) || !is_string($token)) {
                throw new InvalidParamException('Password reset token cannot be blank.');
            }

            $this->_user = User::findByToken($token);

            if (!$this->_user) {
                throw new InvalidParamException('Wrong password reset token.');
            }

            parent::__construct($config);
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'password' => 'Новый пароль',
            ];
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                ['password', 'required'],
                ['password', 'string', 'min' => 6],
            ];
        }

        /**
         * Resets password.
         * @return boolean if password was reset.
         */
        public function resetPassword()
        {
            $user = $this->_user;
            $user->setPassword($this->password);
            $user->removeToken();

            // unconfirmed account will be confirmed
            if ($user->status == User::STATUS_READY) {
                $user->status = User::STATUS_ACTIVE;
            }

            return $user->save(false);
        }
    }
