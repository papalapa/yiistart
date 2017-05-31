<?php

    namespace papalapa\yiistart\modules\images\models;

    use papalapa\yiistart\helpers\FileHelper;
    use papalapa\yiistart\models\MultilingualActiveRecord;
    use papalapa\yiistart\modules\settings\models\Settings;
    use papalapa\yiistart\modules\users\models\BaseUser;
    use papalapa\yiistart\validators\FilePathValidator;
    use papalapa\yiistart\validators\ReorderValidator;
    use papalapa\yiistart\validators\WhiteSpaceNormalizerValidator;
    use yii\behaviors\BlameableBehavior;
    use yii\behaviors\TimestampBehavior;
    use yii\db\Expression;
    use yii\helpers\ArrayHelper;

    /**
     * This is the model class for table "images".
     * @property integer             $id
     * @property integer             $category_id
     * @property string              $title
     * @property string              $text
     * @property string              $image
     * @property integer             $size
     * @property integer             $width
     * @property integer             $height
     * @property integer             $order
     * @property integer             $is_active
     * @property integer             $created_by
     * @property integer             $updated_by
     * @property string              $created_at
     * @property string              $updated_at
     * @property ImagesTranslation[] $imagesTranslations
     * @property ImageCategory       $category
     */
    class Images extends MultilingualActiveRecord
    {
        const SCENARIO_DEVELOPER = 'developer';

        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'images';
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
                'text'        => 'Описание',
                'image'       => 'Изображение',
                'size'        => 'Размер',
                'width'       => 'Ширина',
                'height'      => 'Высота',
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
                    'langClassName' => ImagesTranslation::className(),
                    'tableName'     => ImagesTranslation::tableName(),
                    'attributes'    => ['title', 'text'],
                ],
            ]);
        }

        /**
         * @return array
         */
        public function scenarios()
        {
            return [
                self::SCENARIO_DEFAULT   => ['category_id', 'title', 'text', 'order', 'is_active', 'image'],
                self::SCENARIO_DEVELOPER => ['category_id', 'title', 'text', 'order', 'is_active', 'image'],
            ];
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            $rules = $this->localizedRules([
                [['category_id'], 'required'],
                [['category_id'], 'integer'],
                [
                    ['category_id'], 'exist',
                    'targetClass'     => ImageCategory::className(),
                    'targetAttribute' => 'id',
                    'filter'          => function ($q) {
                        /* @var $q \yii\db\ActiveQuery */
                        return $q->andWhere(['is_visible' => true]);
                    },
                    'on'              => self::SCENARIO_DEFAULT,
                ],
                [
                    ['category_id'], 'exist',
                    'targetClass'     => ImageCategory::className(),
                    'targetAttribute' => 'id',
                    'on'              => self::SCENARIO_DEVELOPER,
                ],

                [['title', 'text'], WhiteSpaceNormalizerValidator::className()],
                [['title'], 'string', 'max' => 128],
                [['text'], 'string'],

                [['order'], 'integer'],
                [['order'], ReorderValidator::className()],
                [['order'], 'required'],

                [['is_active'], 'boolean'],
                [['is_active'], 'default', 'value' => false],

                [['image'], 'required'],
                [['image'], 'string', 'max' => 128, 'enableClientValidation' => false],
                [['image'], FilePathValidator::className()],
            ]);

            if ($rule = Settings::paramOf('images.upload.rule', false)) {
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
         * @param array $data
         * @param null  $formName
         * @return bool
         */
        public function load($data, $formName = null)
        {
            if (\Yii::$app->user->identity->role == BaseUser::ROLE_DEVELOPER) {
                $this->scenario = self::SCENARIO_DEVELOPER;
            }

            return parent::load($data, $formName); // TODO: Change the autogenerated stub
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getImagesTranslations()
        {
            return $this->hasMany(ImagesTranslation::className(), ['content_id' => 'id']);
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getCategory()
        {
            return $this->hasOne(ImageCategory::className(), ['id' => 'category_id']);
        }
    }
