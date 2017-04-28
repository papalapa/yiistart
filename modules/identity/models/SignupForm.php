<?php

    namespace backend\modules\user\models;

    use common\models\User;
    use yii\base\Model;

    /**
     * Class SignupForm
     * @property $email
     * @package frontend\modules\user\models
     */
    class SignupForm extends Model
    {
        public $email;

        /**
         * Labels for fields
         * @return array
         */
        public function attributeLabels()
        {
            return [
                'email' => 'Электронная почта',
            ];
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                ['email', 'filter', 'filter' => 'trim'],
                ['email', 'required'],
                ['email', 'email'],
                ['email', 'string', 'max' => 128],
                ['email', 'unique', 'targetClass' => User::className(), 'message' => 'Данный электронный адрес уже используется.'],
            ];
        }

        /**
         * Signs user up.
         * @return bool
         */
        public function save()
        {
            if ($this->validate()) {
                $user         = new User();
                $user->email  = $this->email;
                $user->role   = User::ROLE_USER;
                $user->status = User::STATUS_READY;
                $user->setPassword(\Yii::$app->security->generateRandomString());
                $user->generateAuthKey();
                $user->generateToken();

                if ($user->save()) {

                    \Yii::$app->session->setFlash('success', 'Пользователь добавлен.');

                    return $user->id;
                }
            }

            return false;
        }
    }
