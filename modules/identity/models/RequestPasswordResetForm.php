<?php

    namespace backend\modules\user\models;

    use common\models\User;
    use yii\base\Model;

    /**
     * Class RequestPasswordResetForm
     * @package backend\modules\user\models
     */
    class RequestPasswordResetForm extends Model
    {
        public $email;
        /* @var $_user User */
        private $_user;

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                ['email', 'filter', 'filter' => 'trim'],
                ['email', 'required'],
                ['email', 'email'],
                [
                    'email',
                    'exist',
                    'targetClass' => User::className(),
                    'message'     => 'Указанный email в базе не найден.',
                ],
            ];
        }

        /**
         * Sends an email with a link, for resetting the password.
         * @return boolean whether the email was send
         */
        public function sendEmail()
        {
            /* @var $user User */
            $user = $this->getUser();

            if ($user) {
                if (!User::isTokenValid($user->token)) {
                    $user->generateToken();
                }

                if ($user->save()) {
                    return \Yii::$app->mailer
                        ->compose(['html' => 'requestPasswordResetToken-html', 'text' => 'requestPasswordResetToken-text'], ['user' => $user])
                        ->setFrom([\Yii::$app->params['noreplyEmail'] => \Yii::$app->name . ' robot'])
                        ->setTo($this->email)
                        ->setSubject('Запрос на сброс пароля для ' . \Yii::$app->name)
                        ->send();
                }
            }

            return false;
        }

        /**
         * Finds user by [[email]]
         * @return User|null
         */
        public function getUser()
        {
            if ($this->_user === null) {
                $this->_user = User::findByEmail($this->email);
            }

            return $this->_user;
        }
    }
