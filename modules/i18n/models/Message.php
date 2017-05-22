<?php

    namespace papalapa\yiistart\modules\i18n\models;

    use papalapa\yiistart\validators\WhiteSpaceNormalizerValidator;
    use yii\db\ActiveRecord;

    /**
     * This is the model class for table "message".
     * @property integer       $id
     * @property string        $language
     * @property string        $translation
     * @property SourceMessage $sourceMessage
     */
    class Message extends ActiveRecord
    {
        /**
         * On missing translation
         */
        const SCENARIO_MISSING = 'missing';

        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'message';
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'id'          => 'ID',
                'language'    => 'Язык',
                'translation' => 'Перевод',
            ];
        }

        /**
         * @return array
         */
        public function scenarios()
        {
            return [
                self::SCENARIO_DEFAULT => ['translation'],
                self::SCENARIO_MISSING => ['language', 'translation'],
            ];
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [['language'], 'required'],
                [['language'], 'string', 'max' => 16],
                [['language'], 'in', 'range' => i18n::locales()],

                [['translation'], 'required', 'on' => [self::SCENARIO_DEFAULT]],
                [['translation'], WhiteSpaceNormalizerValidator::className()],
                [['translation'], 'string'],
            ];
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getSourceMessage()
        {
            return $this->hasOne(SourceMessage::className(), ['id' => 'id']);
        }
    }
