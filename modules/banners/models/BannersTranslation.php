<?php

    namespace papalapa\yiistart\modules\banners\models;

    use yii\db\ActiveRecord;

    /**
     * This is the model class for table "banners_translation".
     * @property integer $id
     * @property string  $language
     * @property integer $content_id
     * @property string  $title
     * @property string  $text
     * @property Banners $content
     */
    class BannersTranslation extends ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'banners_translation';
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
                [['language', 'content_id'], 'required'],
                [['content_id'], 'integer'],
                [['language'], 'string', 'max' => 16],
                [['title', 'text'], 'string', 'max' => 128],
                [['language', 'content_id'], 'unique', 'targetAttribute' => ['language', 'content_id'], 'message' => 'The combination of Language and Content ID has already been taken.'],
                [['content_id'], 'exist', 'skipOnError' => true, 'targetClass' => Banners::className(), 'targetAttribute' => ['content_id' => 'id']],
            ];
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getContent()
        {
            return $this->hasOne(Banners::className(), ['id' => 'content_id']);
        }
    }
