<?php

    namespace papalapa\yiistart\modules\photo\models;

    use papalapa\yiistart\helpers\FileHelper;
    use papalapa\yiistart\models\MultilingualActiveRecord;
    use papalapa\yiistart\validators\FilePathValidator;
    use papalapa\yiistart\validators\WhiteSpaceNormalizerValidator;
    use yii\behaviors\BlameableBehavior;
    use yii\behaviors\TimestampBehavior;
    use yii\db\Expression;
    use yii\helpers\ArrayHelper;

    /**
     * This is the model class for table "photo".
     * @property integer            $id
     * @property string             $title
     * @property string             $text
     * @property string             $image
     * @property integer            $size
     * @property integer            $width
     * @property integer            $height
     * @property integer            $index_number
     * @property integer            $is_active
     * @property integer            $created_by
     * @property integer            $updated_by
     * @property string             $created_at
     * @property string             $updated_at
     * @property PhotoTranslation[] $photoTranslations
     */
    class Photo extends MultilingualActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'photo';
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return $this->localizedAttributes([
                'id'           => 'ID',
                'title'        => 'Заголовок',
                'text'         => 'Описание',
                'image'        => 'Изображение',
                'size'         => 'Размер',
                'width'        => 'Ширина',
                'height'       => 'Высота',
                'index_number' => 'Порядковый номер',
                'is_active'    => 'Активность',
                'created_by'   => 'Кем создано',
                'updated_by'   => 'Кем изменено',
                'created_at'   => 'Дата создания',
                'updated_at'   => 'Дата изменения',
            ]);
        }

        /**
         * @return array
         */
        public function behaviors()
        {
            return ArrayHelper::merge(parent::behaviors(), [
                'TimestampBehavior'    => [
                    'class' => TimestampBehavior::className(),
                    'value' => new Expression('NOW()'),
                ],
                'BlameableBehavior'    => [
                    'class' => BlameableBehavior::className(),
                ],
                'MultilingualBehavior' => [
                    'langClassName' => PhotoTranslation::className(),
                    'tableName'     => PhotoTranslation::tableName(),
                    'attributes'    => ['title', 'text'],
                ],
            ]);
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            $rules = $this->localizedRules([
                [['title', 'text'], WhiteSpaceNormalizerValidator::className()],
                [['title'], 'string', 'max' => 128],
                [['text'], 'string'],

                [['index_number'], 'required'],
                [['index_number'], 'integer'],
                [['index_number'], 'unique'],

                [['is_active'], 'boolean'],
                [['is_active'], 'default', 'value' => false],

                [['image'], 'required'],
                [['image'], 'string', 'max' => 128, 'enableClientValidation' => false],
                [['image'], FilePathValidator::className()],
            ]);

            if ($rule = ArrayHelper::getValue(\Yii::$app->params, 'photo.upload.rule', false)) {
                $rules[] = $rule;
            }

            return $rules;
        }

        /**
         * @param bool $insert
         * @return bool
         */
        public function beforeSave($insert)
        {
            $path         = \Yii::getAlias("@frontend/web{$this->image}");
            $image        = getimagesize($path);
            $this->width  = !empty($image[0]) ? (int)$image[0] : null;
            $this->height = !empty($image[1]) ? (int)$image[1] : null;
            $this->size   = FileHelper::size($path);

            return parent::beforeSave($insert); // TODO: Change the autogenerated stub
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getPhotoTranslations()
        {
            return $this->hasMany(PhotoTranslation::className(), ['content_id' => 'id']);
        }
    }
