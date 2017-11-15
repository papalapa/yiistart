<?php

    namespace papalapa\yiistart\modules\image\models;

    use yii\db\ActiveRecord;

    /**
     * This is the model class for table "app_image_translation".
     *
     * @property integer $id
     * @property string  $language
     * @property integer $content_id
     * @property string  $name
     * @property string  $alt
     * @property string  $title
     * @property string  $src
     * @property string  $twin
     * @property string  $text
     * @property string  $caption
     *
     * @property Image   $content
     */
    class ImageTranslation extends ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'app_image_translation';
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
                'name'       => 'Name',
                'alt'        => 'Alt',
                'title'      => 'Title',
                'src'        => 'Src',
                'twin'       => 'Twin',
                'text'       => 'Text',
                'caption'    => 'Caption',
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
                [['content_id'], 'exist', 'skipOnError' => true, 'targetClass' => Image::className(), 'targetAttribute' => ['content_id' => 'id']],

                [['text', 'caption'], 'string'],
                [['name'], 'string', 'max' => 128],
                [['alt', 'title'], 'string', 'max' => 64],
                [['src', 'twin'], 'string', 'max' => 128],

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
            return $this->hasOne(Image::className(), ['id' => 'content_id']);
        }
    }
