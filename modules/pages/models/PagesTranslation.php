<?php

    namespace papalapa\yiistart\modules\pages\models;

    use papalapa\yiistart\validators\WhiteSpaceNormalizerValidator;
    use yii\db\ActiveRecord;

    /**
     * This is the model class for table "pages_translation".
     * @property integer $id
     * @property string  $language
     * @property integer $content_id
     * @property string  $title
     * @property string  $description
     * @property string  $keywords
     * @property string  $header
     * @property string  $text
     * @property string  $context
     * @property Pages   $content
     */
    class PagesTranslation extends ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'pages_translation';
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'id'          => 'ID',
                'language'    => 'Language',
                'content_id'  => 'Content ID',
                'title'       => 'Title',
                'description' => 'Description',
                'keywords'    => 'Keywords',
                'header'      => 'Header',
                'context'     => 'Context',
                'text'        => 'Text',
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
                [['content_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pages::className(), 'targetAttribute' => ['content_id' => 'id']],

                [['text', 'title', 'header', 'context', 'description', 'keywords'], WhiteSpaceNormalizerValidator::className()],
                [['text'], 'string'],
                [['title', 'header'], 'string', 'max' => 256],
                [['description', 'keywords', 'context'], 'string', 'max' => 1024],

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
            return $this->hasOne(Pages::className(), ['id' => 'content_id']);
        }
    }
