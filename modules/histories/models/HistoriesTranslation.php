<?php

    namespace papalapa\yiistart\modules\histories\models;

    use yii\db\ActiveRecord;

    /**
     * This is the model class for table "histories_translation".
     * @property integer   $id
     * @property string    $language
     * @property integer   $content_id
     * @property string    $title
     * @property string    $text
     * @property Histories $content
     */
    class HistoriesTranslation extends ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'histories_translation';
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
                [['text'], 'string'],
                [['title'], 'string', 'max' => 256],

                [['content_id'], 'required'],
                [['content_id'], 'integer'],
                [
                    ['content_id'],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Histories::className(),
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
            return $this->hasOne(Histories::className(), ['id' => 'content_id']);
        }
    }
