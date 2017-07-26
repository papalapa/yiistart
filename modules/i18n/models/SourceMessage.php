<?php

    namespace papalapa\yiistart\modules\i18n\models;

    use papalapa\yiistart\validators\WhiteSpaceNormalizerValidator;
    use yii\db\ActiveRecord;

    /**
     * This is the model class for table "source_message".
     * @property integer                 $id
     * @property string                  $category
     * @property string                  $message
     * @property Message[]               $messages
     * @property SourceMessageCategories $categoryDescription
     */
    class SourceMessage extends ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'source_message';
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'id'       => 'ID',
                'category' => 'Категория',
                'message'  => 'Текст',
            ];
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [['category', 'message'], WhiteSpaceNormalizerValidator::className()],
                [['message'], 'string'],
                [['category'], 'string', 'max' => 64],
            ];
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getMessages()
        {
            return $this->hasMany(Message::className(), ['id' => 'id'])->indexBy('language');
        }

        /**
         * Populating messages to new sourceMessage
         */
        public function initMessages()
        {
            $messages = [];

            foreach (i18n::locales() as $locale) {
                if (!isset($this->messages[$locale])) {
                    $message           = new Message();
                    $message->id       = $this->id;
                    $message->language = $locale;
                    $messages[$locale] = $message;
                } else {
                    $messages[$locale] = $this->messages[$locale];
                }
            }

            $this->populateRelation('messages', $messages);
        }

        /**
         * Linking related records
         */
        public function saveMessages()
        {
            foreach ($this->messages as $message) {
                $this->link('messages', $message);
                $message->save();
            }
        }

        /**
         * @return bool
         */
        public function isTranslated()
        {
            foreach ($this->messages as $message) {
                if (!$message->translation) {
                    return false;
                }
            }

            return true;
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getCategoryDescription()
        {
            return $this->hasOne(SourceMessageCategories::className(), ['category' => 'category']);
        }
    }
