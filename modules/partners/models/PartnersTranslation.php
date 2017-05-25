<?php

    namespace papalapa\yiistart\modules\partners\models;

    use yii\db\ActiveRecord;

    /**
     * This is the model class for table "partners_translation".
     * @property integer  $id
     * @property string   $language
     * @property integer  $content_id
     * @property string   $alt
     * @property string   $title
     * @property Partners $content
     */
    class PartnersTranslation extends ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'partners_translation';
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
                'alt'        => 'Alt',
                'title'      => 'Title',
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
                [['alt', 'title'], 'string', 'max' => 128],
                [['language', 'content_id'], 'unique', 'targetAttribute' => ['language', 'content_id'], 'message' => 'The combination of Language and Content ID has already been taken.'],
                [['content_id'], 'exist', 'skipOnError' => true, 'targetClass' => Partners::className(), 'targetAttribute' => ['content_id' => 'id']],
            ];
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getContent()
        {
            return $this->hasOne(Partners::className(), ['id' => 'content_id']);
        }
    }
