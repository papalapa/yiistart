<?php

    namespace backend\modules\user\models;

    use common\helpers\Timer;
    use common\models\User;
    use yii\base\Model;

    /**
     * Class ProfileForm
     * This is the model class for table "user".
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
     * @property string  $last_ip
     * @package backend\modules\user\models
     */
    class SecureForm extends Model
    {
        public $password;
        public $new_password;
        public $new_password_repeat;
        /**
         * @var User
         */
        private $_user;

        public function __construct()
        {
            $this->_user      = User::findOne(['id' => User::identity('id')]);
            $this->attributes = $this->_user->attributes;

            parent::__construct();
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'password'            => 'Старый пароль',
                'new_password'        => 'Новый пароль',
                'new_password_repeat' => 'Подтверждение нового пароля',
            ];
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [['password', 'new_password', 'new_password_repeat'], 'required'],
                ['password', 'validatePassword'],
                ['new_password', 'string', 'min' => 6],
                ['new_password_repeat', 'compare', 'compareAttribute' => 'new_password', 'message' => 'Пароли не совпадают'],
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
            if (!$this->_user->validatePassword($this->password)) {
                $this->addError($attribute, 'Старый пароль указан неверно.');
            }
        }

        /**
         * @return bool
         */
        public function saveProfile()
        {
            $this->_user->setPassword($this->new_password);

            return $this->_user->save();
        }
    }
