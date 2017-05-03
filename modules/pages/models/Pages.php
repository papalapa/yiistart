<?php

    namespace papalapa\yiistart\modules\pages\models;

    use papalapa\yiistart\models\MultilingualActiveRecord;
    use papalapa\yiistart\validators\WhiteSpaceNormalizerValidator;
    use yii\base\UnknownMethodException;
    use yii\behaviors\BlameableBehavior;
    use yii\behaviors\TimestampBehavior;
    use yii\db\ActiveQuery;
    use yii\db\Expression;
    use yii\helpers\ArrayHelper;

    /**
     * This is the model class for table "pages".
     * @property integer            $id
     * @property string             $title
     * @property string             $description
     * @property string             $keywords
     * @property string             $header
     * @property string             $context
     * @property string             $text
     * @property string             $image
     * @property integer            $is_active
     * @property integer            $created_by
     * @property integer            $updated_by
     * @property string             $created_at
     * @property string             $updated_at
     * @property PagesTranslation[] $pagesTranslations
     */
    class Pages extends MultilingualActiveRecord
    {
        /**
         * Pages module
         * @var \papalapa\yiistart\modules\pages\Module
         */
        public $module;
        /**
         * @var boolean
         */
        public $multilingual;

        /**
         * @inheritdoc
         */
        public function init()
        {
            $this->module       = \Yii::$app->getModule('pages');
            $this->multilingual = $this->module->multilingual;

            parent::init();
        }

        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'pages';
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            $attributes = [
                'id'          => 'ID',
                'title'       => 'Мета-тег Title',
                'description' => 'Мета-тег Description',
                'keywords'    => 'Мета-тег Keywords',
                'header'      => 'Заголовок',
                'context'     => 'Описание',
                'text'        => 'Текст',
                'image'       => 'Изображение',
                'is_active'   => 'Активность',
                'created_by'  => 'Кем создано',
                'updated_by'  => 'Кем изменено',
                'created_at'  => 'Дата создания',
                'updated_at'  => 'Дата изменения',
            ];

            if ($this->multilingual) {
                $attributes = $this->localizedAttributes($attributes);
            }

            return $attributes;
        }

        /**
         * @return array
         */
        public function behaviors()
        {
            $behaviors = [
                'TimestampBehavior' => [
                    'class' => TimestampBehavior::className(),
                    'value' => new Expression('NOW()'),
                ],
                'BlameableBehavior' => [
                    'class' => BlameableBehavior::className(),
                ],
            ];

            if ($this->multilingual) {
                $behaviors['MultilingualBehavior'] = [
                    'langClassName' => PagesTranslation::className(),
                    'tableName'     => PagesTranslation::tableName(),
                    'attributes'    => ['title', 'description', 'keywords', 'header', 'context', 'text'],
                ];

                $behaviors = ArrayHelper::merge(parent::behaviors(), $behaviors);
            }

            return $behaviors;
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            $rules = [
                [['text', 'title', 'header', 'context', 'description', 'keywords'], WhiteSpaceNormalizerValidator::className()],
                [['text'], 'string'],
                [['title', 'header'], 'string', 'max' => 256],
                [['description', 'keywords', 'context'], 'string', 'max' => 1024],

                [['is_active'], 'boolean'],
                [['is_active'], 'default', 'value' => 0],

                [['image'], 'string', 'max' => 128, 'enableClientValidation' => false],
                [['image'], 'validateImage'],
            ];

            if ($this->multilingual) {
                $rules = $this->localizedRules($rules);
            }

            return $rules;
        }

        /**
         * @return  ActiveQuery|\papalapa\yiistart\models\MultilingualActiveQuery
         */
        public static function find()
        {
            if ((new static())->multilingual) {
                return parent::find();
            }

            return new ActiveQuery(get_called_class());
        }

        /**
         * Validate image path, that must be a local
         * @return bool
         */
        public function validateImage()
        {
            return call_user_func($this->module->validateImage, $this->image);
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getPagesTranslations()
        {
            if ($this->multilingual) {
                return $this->hasMany(PagesTranslation::className(), ['content_id' => 'id']);
            }

            throw new UnknownMethodException();
        }
    }
