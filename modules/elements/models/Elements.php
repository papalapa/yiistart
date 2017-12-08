<?php

    namespace papalapa\yiistart\modules\elements\models;

    use papalapa\yiistart\models\MultilingualActiveRecord;
    use papalapa\yiistart\validators\HtmlPurifierValidator;
    use papalapa\yiistart\validators\TagsStripperValidator;
    use papalapa\yiistart\validators\TelephoneValidator;
    use papalapa\yiistart\validators\WhiteSpaceNormalizerValidator;
    use yii\behaviors\BlameableBehavior;
    use yii\behaviors\TimestampBehavior;
    use yii\caching\TagDependency;
    use yii\db\Expression;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;

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
     * @property ElementsTranslation $translations
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
            return $this->localizedScenarios([
                self::SCENARIO_DEFAULT   => ['text', 'is_active'],
                self::SCENARIO_DEVELOPER => ['alias', 'category_id', 'name', 'text', 'format', 'pattern', 'description', 'is_active'],
            ]);
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
                [
                    ['text'],
                    TagsStripperValidator::className(),
                    'when' => function ($model) /* @var $model Elements */ {
                        return ($model->format <> self::FORMAT_HTML) && ($model->format <> self::FORMAT_RAW);
                    },
                ],
                [
                    ['text'],
                    TelephoneValidator::className(),
                    'when'                   => function ($model) /* @var $model Elements */ {
                        return $model->format == self::FORMAT_TEL;
                    },
                    'message'                => 'Введите корректный номер телефона',
                    'enableClientValidation' => false,
                ],
                [
                    ['text'],
                    'email',
                    'when'                   => function ($model) /* @var $model Elements */ {
                        return $model->format == self::FORMAT_EMAIL;
                    },
                    'enableClientValidation' => false,
                ],
                [
                    ['text'], HtmlPurifierValidator::className(),
                    'when' => function ($model) /* @var $model Elements */ {
                        return $model->format == self::FORMAT_HTML;
                    },
                ],

                [['name'], WhiteSpaceNormalizerValidator::className()],
                [['name'], 'string', 'max' => 128],

                [['format'], 'string', 'max' => 16],
                [['format'], 'in', 'range' => array_keys(self::formats())],
                [['format'], 'default', 'value' => self::FORMAT_RAW],

                [['pattern'], WhiteSpaceNormalizerValidator::className()],
                [['pattern'], 'string', 'max' => 128],

                [['description'], WhiteSpaceNormalizerValidator::className()],
                [['description'], 'string'],

                [['is_active'], 'boolean'],
                [['is_active'], 'default', 'value' => 0, 'on' => [self::SCENARIO_DEFAULT]],
                [['is_active'], 'default', 'value' => 1, 'on' => [self::SCENARIO_DEVELOPER]],
            ]);
        }

        /**
         * @param bool  $insert
         * @param array $changedAttributes
         */
        public function afterSave($insert, $changedAttributes)
        {
            parent::afterSave($insert, $changedAttributes);

            TagDependency::invalidate(\Yii::$app->cache, get_called_class());
        }

        /**
         * @return array
         */
        public static function formats()
        {
            return [
                self::FORMAT_HTML  => 'HTML',
                self::FORMAT_TEXT  => 'Текст',
                self::FORMAT_EMAIL => 'Email',
                self::FORMAT_TEL   => 'Телефон',
                self::FORMAT_RAW   => 'Без форматирования',
            ];
        }

        /**
         * Returns html element by key
         * @param integer|string $key
         * @param string|null    $default
         * @return null|string
         */
        public static function valueOf($key, $default = null)
        {
            /* @var $model self */
            $model = \Yii::$app->db->cache(function () use ($key) {
                $id    = is_numeric($key) ? $key : null;
                $alias = !is_numeric($key) ? $key : null;

                return static::find()->andFilterWhere(['alias' => $alias])->andFilterWhere(['id' => $id])->one();
            }, ArrayHelper::getValue(\Yii::$app->params, 'cache.duration.element'), new TagDependency(['tags' => get_called_class()]));

            if (is_null($model) && !is_numeric($key)) {
                $model           = new static();
                $model->scenario = self::SCENARIO_DEVELOPER;
                $model->detachBehavior('BlameableBehavior');
                $model->alias      = $key;
                $model->name       = $key;
                $model->text       = $default;
                $model->created_by = 0;
                $model->updated_by = 0;

                if ($model->validate(['alias', 'name', 'text', 'format', 'is_active']) && $model->save(false)) {
                    \Yii::info(sprintf('Создан несуществующий HTML элемент "%s".', $key));

                    return $model->text;
                } else {
                    foreach ($model->firstErrors as $firstError) {
                        \Yii::error(sprintf('Произошла ошибка при попытке создания HTML элемента "%s": %s.', $key, $firstError));
                    }

                    return $default;
                }
            }

            if (!$model->is_active) {
                \Yii::info(sprintf('Используется отключенный HTML элемент "%s".', $key));

                return $default;
            }

            switch ($model->format) {
                case self::FORMAT_TEL:
                    return Html::a($model->text, sprintf('tel:%s', $model->text));
                case self::FORMAT_HTML:
                    return \Yii::$app->formatter->asHtml($model->text);
                case self::FORMAT_EMAIL:
                    return \Yii::$app->formatter->asEmail($model->text);
                case self::FORMAT_TEXT:
                    return \Yii::$app->formatter->asText($model->text);
                default:
                    return \Yii::$app->formatter->asRaw($model->text);
            }
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
        public function getTranslations()
        {
            return $this->hasMany(ElementsTranslation::className(), ['content_id' => 'id']);
        }
    }
