<?php

    namespace papalapa\yiistart\modules\banners\models;

    use papalapa\yiistart\models\MultilingualActiveRecord;
    use papalapa\yiistart\modules\settings\models\Settings;
    use papalapa\yiistart\validators\FilePathValidator;
    use papalapa\yiistart\validators\ReorderValidator;
    use papalapa\yiistart\validators\WhiteSpaceNormalizerValidator;
    use yii\behaviors\BlameableBehavior;
    use yii\behaviors\TimestampBehavior;
    use yii\db\Expression;
    use yii\helpers\ArrayHelper;

    /**
     * This is the model class for table "banners".
     * @property integer              $id
     * @property integer              $category_id
     * @property string               $title
     * @property string               $text
     * @property string               $link
     * @property string               $image
     * @property integer              $order
     * @property integer              $is_active
     * @property integer              $created_by
     * @property integer              $updated_by
     * @property string               $created_at
     * @property string               $updated_at
     * @property BannersCategory      $category
     * @property BannersTranslation[] $translations
     */
    class Banners extends MultilingualActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'banners';
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return $this->localizedAttributes([
                'id'          => 'ID',
                'category_id' => 'Категория',
                'title'       => 'Заголовок',
                'text'        => 'Текст',
                'link'        => 'Ссылка',
                'image'       => 'Изображение',
                'order'       => 'Порядковый номер',
                'is_active'   => 'Активность',
                'created_by'  => 'Кем создано',
                'updated_by'  => 'Кем изменено',
                'created_at'  => 'Дата создания',
                'updated_at'  => 'Дата изменения',
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
                    'langClassName' => BannersTranslation::className(),
                    'tableName'     => BannersTranslation::tableName(),
                    'attributes'    => ['title', 'text'],
                ],
            ]);
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            $rules = [
                [['category_id'], 'integer'],
                [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => BannersCategory::className(), 'targetAttribute' => ['category_id' => 'id']],

                [['title', 'text', 'link'], WhiteSpaceNormalizerValidator::className()],
                [['title'], 'string', 'max' => 256],
                [['link'], 'string', 'max' => 1024],
                [['text'], 'string'],

                [['order'], 'integer'],
                [['order'], ReorderValidator::className(), 'extraFields' => ['category_id']],
                [['order'], 'required'],

                [['is_active'], 'boolean'],
                [['is_active'], 'default', 'value' => false],

                [['image'], 'required'],
                [['image'], 'string', 'max' => 128, 'enableClientValidation' => false],
                [['image'], FilePathValidator::className()],
            ];

            if ($rule = Settings::paramOf('banners.upload.rule', false)) {
                $rules[] = $rule;
            }

            return $this->localizedRules($rules);
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getCategory()
        {
            return $this->hasOne(BannersCategory::className(), ['id' => 'category_id']);
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getTranslations()
        {
            return $this->hasMany(BannersTranslation::className(), ['content_id' => 'id']);
        }
    }
