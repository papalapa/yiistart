<?php

    namespace papalapa\yiistart\modules\elements\models;

    use papalapa\yiistart\models\MultilingualActiveRecord;
    use papalapa\yiistart\modules\users\models\BaseUser;
    use papalapa\yiistart\validators\HtmlPurifierValidator;
    use papalapa\yiistart\validators\TagsStripperValidator;
    use papalapa\yiistart\validators\WhiteSpaceNormalizerValidator;
    use yii\behaviors\BlameableBehavior;
    use yii\behaviors\TimestampBehavior;
    use yii\db\Expression;
    use yii\helpers\ArrayHelper;

    /**
     * This is the model class for table "elements".
     * @property integer             $id
     * @property string              $alias
     * @property integer             $category_id
     * @property string              $name
     * @property string              $text
     * @property string              $format
     * @property string              $pattern
     * @property string              $description
     * @property integer             $is_active
     * @property integer             $created_by
     * @property integer             $updated_by
     * @property string              $created_at
     * @property string              $updated_at
     * @property ElementCategory     $category
     * @property ElementsTranslation $elementsTranslations
     */
    class Elements extends MultilingualActiveRecord
    {
        const SCENARIO_DEVELOPER = 'developer';
        const FORMAT_HTML        = 'html';
        const FORMAT_TEXT        = 'text';
        const FORMAT_EMAIL       = 'email';
        const FORMAT_TEL         = 'tel';
        const FORMAT_RAW         = 'raw';
        const ICO_FORMAT_HTML    = 'html';
        const ICO_FORMAT_TEXT    = 'text';
        const ICO_FORMAT_EMAIL   = 'email';
        const ICO_FORMAT_TEL     = 'tel';

        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'elements';
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return $this->localizedAttributes([
                'id'          => 'ID',
                'alias'       => 'Альяс',
                'category_id' => 'Категория',
                'name'        => 'Название',
                'text'        => 'Текст',
                'format'      => 'Формат',
                'pattern'     => 'Шаблон',
                'description' => 'Описание',
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
                    'langClassName' => ElementsTranslation::className(),
                    'tableName'     => ElementsTranslation::tableName(),
                    'attributes'    => ['text'],
                ],
            ]);
        }

        /**
         * @return array
         */
        public function scenarios()
        {
            return [
                self::SCENARIO_DEFAULT   => ['text', 'is_active'],
                self::SCENARIO_DEVELOPER => ['alias', 'category_id', 'name', 'text', 'format', 'pattern', 'description', 'is_active'],
            ];
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return $this->localizedRules([
                [['alias'], WhiteSpaceNormalizerValidator::className()],
                [['alias'], 'string', 'max' => 64],
                [['alias'], 'match', 'pattern' => '/^[a-z]+(\.[a-z]+)*$/'],
                [['alias'], 'unique'],

                [['category_id'], 'required'],
                [['category_id'], 'integer'],
                [
                    ['category_id'],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => ElementCategory::className(),
                    'targetAttribute' => ['category_id' => 'id'],
                ],

                [['text'], WhiteSpaceNormalizerValidator::className()],
                [['text'], 'string'],
                [['text'], 'required'],
                [
                    ['text'],
                    TagsStripperValidator::className(),
                    'when' => function ($model) /* @var $model Elements */ {
                        return ($model->format <> self::FORMAT_HTML) && ($model->format <> self::FORMAT_RAW);
                    },
                ],
                [['text', 'name'], WhiteSpaceNormalizerValidator::className()],
                [
                    ['text'],
                    'email',
                    'when'                   => function ($model) /* @var $model Elements */ {
                        return $model->format == self::FORMAT_EMAIL;
                    },
                    'enableClientValidation' => false,
                ],
                [
                    ['text'],
                    'match',
                    'pattern'                => '/^(\+?\d+( ?\(\d+\))?|\(\+?\d+\)) ?(\d+(-| )?)*\d+$/',
                    'when'                   => function ($model) /* @var $model Elements */ {
                        return $model->format == self::FORMAT_TEL;
                    },
                    'message'                => 'Введите корректный номер телефона',
                    'enableClientValidation' => false,
                ],
                [
                    ['text'], HtmlPurifierValidator::className(),
                    'when' => function ($model) /* @var $model Elements */ {
                        return $model->format == self::FORMAT_HTML;
                    },
                ],

                [['name'], WhiteSpaceNormalizerValidator::className()],
                [['name'], 'required'],
                [['name'], 'string', 'max' => 64],

                [['format'], 'required'],
                [['format'], 'string', 'max' => 16],
                [['format'], 'in', 'range' => array_keys(self::formats())],

                [['pattern'], WhiteSpaceNormalizerValidator::className()],
                [['pattern'], 'string', 'max' => 128],

                [['description'], WhiteSpaceNormalizerValidator::className()],
                [['description'], 'string', 'max' => 256],

                [['is_active'], 'boolean'],
                [['is_active'], 'default', 'value' => 0],
            ]);
        }

        /**
         * @return array
         */
        public static function formats()
        {
            return [
                self::FORMAT_TEXT  => 'Текст',
                self::FORMAT_HTML  => 'HTML',
                self::FORMAT_EMAIL => 'Email',
                self::FORMAT_TEL   => 'Телефон',
                self::FORMAT_RAW   => 'Без форматирования',
            ];
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

            return parent::load($data, $formName);
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getCategory()
        {
            return $this->hasOne(ElementCategory::className(), ['id' => 'category_id']);
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getElementsTranslations()
        {
            return $this->hasMany(ElementsTranslation::className(), ['content_id' => 'id']);
        }
    }
