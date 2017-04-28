<?php

    namespace papalapa\yiistart\modules\settings\models;

    use papalapa\yiistart\models\User;
    use papalapa\yiistart\validators\WhiteSpaceNormalizerValidator;
    use yii\behaviors\BlameableBehavior;
    use yii\behaviors\TimestampBehavior;
    use yii\db\ActiveRecord;
    use yii\db\Expression;

    /**
     * This is the model class for table "settings".
     * @property integer $id
     * @property string  $key
     * @property string  $value
     * @property integer $is_active
     * @property integer $created_by
     * @property integer $updated_by
     * @property string  $created_at
     * @property string  $updated_at
     */
    class Settings extends ActiveRecord
    {
        const SCENARIO_DEVELOPER = 'developer';

        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'settings';
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'id'         => 'ID',
                'key'        => 'Ключ',
                'value'      => 'Значение',
                'is_active'  => 'Активность',
                'created_by' => 'Кем создано',
                'updated_by' => 'Кем изменено',
                'created_at' => 'Дата создания',
                'updated_at' => 'Дата изменения',
            ];
        }

        /**
         * @return array
         */
        public function behaviors()
        {
            return [
                'TimestampBehavior' => [
                    'class' => TimestampBehavior::className(),
                    'value' => new Expression('NOW()'),
                ],
                'BlameableBehavior' => [
                    'class' => BlameableBehavior::className(),
                ],
            ];
        }

        public function scenarios()
        {
            return [
                self::SCENARIO_DEFAULT   => ['value', 'is_active'],
                self::SCENARIO_DEVELOPER => ['key', 'value', 'is_active'],
            ];
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [['key'], WhiteSpaceNormalizerValidator::className()],
                [['key'], 'required'],
                [['key'], 'string', 'max' => 64],
                [['key'], 'unique'],

                [['value'], WhiteSpaceNormalizerValidator::className()],
                [['value'], 'string'],

                [['is_active'], 'boolean'],
                [['is_active'], 'default', 'value' => 0],
            ];
        }

        /**
         * @param array $data
         * @param null  $formName
         * @return bool
         */
        public function load($data, $formName = null)
        {
            if (User::identity()->role == User::ROLE_DEVELOPER) {
                $this->scenario = self::SCENARIO_DEVELOPER;
            }

            return parent::load($data, $formName);
        }
    }
