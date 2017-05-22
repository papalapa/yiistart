<?php

    namespace papalapa\yiistart\modules\settings\models;

    use papalapa\yiistart\validators\WhiteSpaceNormalizerValidator;
    use yii\db\ActiveRecord;

    /**
     * This is the model class for table "settings_translation".
     * @property integer  $id
     * @property string   $language
     * @property integer  $content_id
     * @property string   $value
     * @property Settings $content
     */
    class SettingsTranslation extends ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'settings_translation';
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'id'         => 'ID',
                'language'   => 'Language',
                'content_id' => 'Content ID',
                'value'      => 'Value',
            ];
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [['content_id'], 'required'],
                [['content_id'], 'integer'],
                [['content_id'], 'exist', 'skipOnError' => true, 'targetClass' => Settings::className(), 'targetAttribute' => ['content_id' => 'id']],

                [['value'], WhiteSpaceNormalizerValidator::className()],
                [['value'], 'required'],
                [['value'], 'string'],

                [['language'], 'required'],
                [['language'], 'string', 'max' => 16],
                [
                    ['language', 'content_id'],
                    'unique',
                    'targetAttribute' => ['language', 'content_id'],
                    'message'         => 'The combination of Language and Content ID has already been taken.',
                ],
            ];
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getContent()
        {
            return $this->hasOne(Settings::className(), ['id' => 'content_id']);
        }
    }
