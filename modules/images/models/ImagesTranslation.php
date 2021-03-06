<?php

    namespace papalapa\yiistart\modules\images\models;

    use papalapa\yiistart\validators\WhiteSpaceNormalizerValidator;
    use yii\db\ActiveRecord;

    /**
     * This is the model class for table "image_translation".
     * @property integer $id
     * @property string  $language
     * @property integer $content_id
     * @property string  $title
     * @property string  $text
     * @property Images  $content
     */
    class ImagesTranslation extends ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'images_translation';
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
                'title'      => 'Title',
                'text'       => 'Text',
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
                [['content_id'], 'exist', 'skipOnError' => true, 'targetClass' => Images::className(), 'targetAttribute' => ['content_id' => 'id']],

                [['title', 'text'], WhiteSpaceNormalizerValidator::className()],
                [['title'], 'string', 'max' => 128],
                [['text'], 'string'],

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
            return $this->hasOne(Images::className(), ['id' => 'content_id']);
        }
    }
