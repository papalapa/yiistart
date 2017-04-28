<?php

    namespace backend\modules\user\models;

    use common\models\User;
    use yii\base\Model;

    /**
     * Class LoginForm
     * @package backend\modules\user\models
     */
    class LoginForm extends Model
    {
        public  $email;
        public  $password;
        public  $rememberMe = true;
        private $_user;

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [['email', 'password'], 'required'],
                ['email', 'email'],
                ['email', 'checkStatus'],
                ['rememberMe', 'boolean', 'enableClientValidation' => false],
                ['rememberMe', 'default', 'value' => false],
                ['password', 'validatePassword', 'enableClientValidation' => false],
            ];
        }

        /**
         * Labels for fields
         * @return array
         */
        public function attributeLabels()
        {
            return [
                'email'      => 'Электронная почта',
                'rememberMe' => 'Запомнить меня',
                'password'   => 'Пароль',
            ];
        }

        /**
         * Validates the password.
         * This method serves as the inline validation for password.
         * @param string $attribute the attribute currently being validated
         * @param array  $params    the additional name-value pairs given in the rule
         */
        public function validatePassword($attribute, $params)
        {
            if (!$this->hasErrors()) {
                $user = $this->getUser();
                if (!$user || !$user->validatePassword($this->password)) {
                    $this->addError($attribute, 'Неверный адрес электронной почты или пароль.');
                }
            }
        }

        /**
         * Finds user by [[email]]
         * @return User|null
         */
        protected function getUser()
        {
            if ($this->_user === null) {
                $this->_user = User::findByEmail($this->email);
            }

            return $this->_user;
        }

        /**
         * Validate account status
         * For access account must be an active
         * @param $attribute
         * @param $params
         */
        public function checkStatus($attribute, $params)
        {
            if (!$this->hasErrors()) {
                $user = $this->getUser();
                if (!$user || $user->status !== User::STATUS_ACTIVE) {
                    $this->addError($attribute, 'Email не был подтвержден после регистрации.');
                }
            }
        }

        /**
         * Logs in a user using the provided email and password.
         * @return boolean whether the user is logged in successfully
         */
        public function login()
        {
            if ($this->validate()) {
                if ($user = $this->getUser()) {
                    $user->touch('activity_at');
                    $user->updateAttributes(['last_ip' => ip2long(\Yii::$app->request->userIP)]);

                    return User::login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
                }
            }

            return false;
        }
    }
