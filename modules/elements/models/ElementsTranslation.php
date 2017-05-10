<?php

    namespace papalapa\yiistart\modules\elements\models;

    use papalapa\yiistart\validators\WhiteSpaceNormalizerValidator;
    use yii\db\ActiveRecord;

    /**
     * This is the model class for table "elements_translation".
     * @property integer  $id
     * @property string   $language
     * @property integer  $content_id
     * @property string   $text
     * @property Elements $content
     */
    class ElementsTranslation extends ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'elements_translation';
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
                'text'       => 'Text',
            ];
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [['text'], WhiteSpaceNormalizerValidator::className()],
                [['text'], 'required'],
                [['text'], 'string'],

                [['content_id'], 'required'],
                [['content_id'], 'integer'],
                [
                    ['content_id'],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Elements::className(),
                    'targetAttribute' => ['content_id' => 'id'],
                ],

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
            return $this->hasOne(Elements::className(), ['id' => 'content_id']);
        }
    }
