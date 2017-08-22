<?php

    namespace papalapa\yiistart\modules\settings\models;

    use papalapa\yiistart\models\MultilingualActiveRecord;
    use papalapa\yiistart\modules\i18n\models\i18n;
    use papalapa\yiistart\validators\FilePathValidator;
    use papalapa\yiistart\validators\TelephoneValidator;
    use papalapa\yiistart\validators\WhiteSpaceNormalizerValidator;
    use yii\behaviors\BlameableBehavior;
    use yii\behaviors\TimestampBehavior;
    use yii\caching\TagDependency;
    use yii\db\Expression;
    use yii\helpers\ArrayHelper;

    /**
     * This is the model class for table "settings".
     * @property integer               $id
     * @property string                $title
     * @property string                $key
     * @property integer               $type
     * @property string                $pattern
     * @property boolean               $multilingual
     * @property string                $value
     * @property string                $comment
     * @property boolean               $is_active
     * @property boolean               $is_visible
     * @property integer               $created_by
     * @property integer               $updated_by
     * @property string                $created_at
     * @property string                $updated_at
     * @property SettingsTranslation[] $translations
     */
    class Settings extends MultilingualActiveRecord
    {
        const SCENARIO_DEVELOPER = 'developer';
        const TYPE_STRING        = 0;
        const TYPE_TEXT          = 5;
        const TYPE_HTML          = 10;
        const TYPE_MAP           = 20;
        const TYPE_TEL           = 30;
        const TYPE_EMAIL         = 40;
        const TYPE_BOOLEAN       = 50;
        const TYPE_IMAGE         = 60;
        const TYPE_URL           = 70;

        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'settings';
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return $this->localizedAttributes([
                'id'           => 'ID',
                'title'        => 'Название',
                'key'          => 'Ключ',
                'type'         => 'Тип поля',
                'pattern'      => 'Шаблон',
                'value'        => 'Значение',
                'multilingual' => 'Мультиязычность',
                'comment'      => 'Комментарий',
                'is_active'    => 'Активность',
                'is_visible'   => 'Видимость в списке',
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
                    'langClassName' => SettingsTranslation::className(),
                    'tableName'     => SettingsTranslation::tableName(),
                    'attributes'    => ['value'],
                ],
            ]);
        }

        /**
         * @return mixed
         */
        public function scenarios()
        {
            return $this->localizedScenarios([
                self::SCENARIO_DEFAULT   => ['value', 'is_active'],
                self::SCENARIO_DEVELOPER => ['title', 'key', 'pattern', 'type', 'multilingual', 'value', 'comment', 'is_active', 'is_visible'],
            ]);
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return $this->localizedRules([
                [['title'], WhiteSpaceNormalizerValidator::className()],
                [['title'], 'string', 'max' => 64],

                [['comment'], 'string', 'max' => 1024],

                [['key'], WhiteSpaceNormalizerValidator::className()],
                [['key'], 'required'],
                [['key'], 'string', 'max' => 64],
                [['key'], 'match', 'pattern' => '/^[a-z]+(\.[a-z]+[0-9]*)*$/'],
                [['key'], 'unique'],

                [['value'], WhiteSpaceNormalizerValidator::className()],
                [['value'], 'string'],

                /* ========================================================================================================== */
                /* Email validation rules */
                /* ========================================================================================================== */
                [
                    /* Split multiple emails comma separated and return array of emails */
                    ['value'], 'filter',
                    'filter' => function ($value) {
                        return (array) preg_split('/\s*[\;\,]\s*/', $value);
                    },
                    'when'   => function ($model) {
                        return $model->type == self::TYPE_EMAIL;
                    },
                ],
                [
                    /* Check each email */
                    ['value'], 'each',
                    'rule'                   => ['email'],
                    'when'                   => function ($model) {
                        return $model->type == self::TYPE_EMAIL;
                    },
                    'enableClientValidation' => false,
                ],
                [
                    /* Join emails with comma */
                    ['value'], 'filter',
                    'filter'      => function ($value) {
                        return implode(', ', $value);
                    },
                    'when'        => function ($model) {
                        return $model->type == self::TYPE_EMAIL;
                    },
                    'skipOnError' => false,
                ],

                /* ========================================================================================================== */
                /* Url validation rule */
                /* ========================================================================================================== */
                [
                    ['value'], 'url',
                    'when'                   => function ($model) {
                        return $model->type == self::TYPE_URL;
                    },
                    'enableClientValidation' => false,
                ],

                /* ========================================================================================================== */
                /* Tel validation rules */
                /* ========================================================================================================== */
                [
                    /* Split multiple tels comma separated and return array of tels */
                    ['value'], 'filter',
                    'filter' => function ($value) {
                        return (array) preg_split('/\s*[\;\,]\s*/', $value);
                    },
                    'when'   => function ($model) {
                        return $model->type == self::TYPE_TEL;
                    },
                ],
                [
                    /* Check each tel */
                    ['value'], 'each',
                    'rule'                   => [TelephoneValidator::className()],
                    'when'                   => function ($model) {
                        return $model->type == self::TYPE_TEL;
                    },
                    'enableClientValidation' => false,
                ],
                [
                    /* Join tels with comma */
                    ['value'], 'filter',
                    'filter'      => function ($value) {
                        return implode(', ', $value);
                    },
                    'when'        => function ($model) {
                        return $model->type == self::TYPE_TEL;
                    },
                    'skipOnError' => false,
                ],

                /* ========================================================================================================== */
                /* Boolean flag validation rules */
                /* ========================================================================================================== */
                [
                    ['value'], 'boolean',
                    'when'                   => function ($model) {
                        return $model->type == self::TYPE_BOOLEAN;
                    },
                    'enableClientValidation' => false,
                ],
                [
                    ['value'], 'default', 'value' => 0,
                    'when'                        => function ($model) {
                        return $model->type == self::TYPE_BOOLEAN;
                    },
                    'enableClientValidation'      => false,
                ],

                /* ========================================================================================================== */
                /* Image validation rules */
                /* ========================================================================================================== */
                [
                    ['value'], 'string',
                    'when'                   => function ($model) {
                        return $model->type == self::TYPE_IMAGE;
                    },
                    'enableClientValidation' => false,
                ],
                [
                    ['value'], FilePathValidator::className(),
                    'when' => function ($model) {
                        return $model->type == self::TYPE_IMAGE;
                    },
                ],

                /* ========================================================================================================== */
                /* Pattern matching validation rule */
                /* ========================================================================================================== */
                [
                    ['value'],
                    function ($attribute, $params, $validator) {
                        try {
                            if (!preg_match($this->pattern, $this->{$attribute})) {
                                $this->addError($attribute, 'Значение не соответствует требуемому шаблону');
                            }
                        } catch (\Exception $e) {
                            $this->addError('pattern', 'Шаблон указан некорректно');
                        }
                    },
                    'when' => function ($model) {
                        return $model->pattern;
                    },
                ],

                [['is_active'], 'boolean'],
                [['is_active'], 'default', 'value' => false, 'on' => [self::SCENARIO_DEFAULT]],
                [['is_active'], 'default', 'value' => true, 'on' => [self::SCENARIO_DEVELOPER]],

                [['is_visible'], 'boolean'],
                [['is_visible'], 'default', 'value' => true],

                [['multilingual'], 'boolean'],
                [['multilingual'], 'default', 'value' => 0],

                [['type'], 'default', 'value' => self::TYPE_STRING],
                [['type'], 'in', 'range' => array_keys(self::types())],
            ]);
        }

        /**
         * Reset multilingual attributes on non-multilingual setting
         */
        public function beforeValidate()
        {
            if (!$this->multilingual) {
                foreach (i18n::locales() as $locale) {
                    $this->{'value_'.$locale} = null;
                }
            }

            return parent::beforeValidate();
        }

        /**
         * Fill multilingual fields the same values as default on multilingual setting
         * @param bool $insert
         * @return bool
         */
        public function beforeSave($insert)
        {
            if (parent::beforeSave($insert)) {
                if (!$this->multilingual) {
                    foreach (i18n::locales() as $locale) {
                        $this->{'value_'.$locale} = $this->value;
                    }
                }

                return true;
            }

            return false;
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
        public static function types()
        {
            return [
                self::TYPE_STRING  => 'Текстовое поле',
                self::TYPE_TEXT    => 'Текст',
                self::TYPE_HTML    => 'HTML код',
                self::TYPE_MAP     => 'Метка на карте',
                self::TYPE_TEL     => 'Телефон',
                self::TYPE_EMAIL   => 'Email',
                self::TYPE_BOOLEAN => 'Переключатель',
                self::TYPE_IMAGE   => 'Изображение',
                self::TYPE_URL     => 'URL ссылка',
            ];
        }

        /**
         * Returns a value of a key in settings db
         * @param string      $key
         * @param string|null $default
         * @return null|boolean|string
         */
        public static function valueOf($key, $default = null)
        {
            /* @var $model self */
            $model = \Yii::$app->db->cache(function () use ($key) {
                return static::find()->multilingual()->where(['key' => $key])->one();
            }, ArrayHelper::getValue(\Yii::$app->params, 'cache.duration.setting'), new TagDependency(['tags' => get_called_class()]));

            if (is_null($model)) {
                $model           = new static();
                $model->scenario = self::SCENARIO_DEVELOPER;
                $model->detachBehavior('BlameableBehavior');
                $model->key        = $key;
                $model->value      = $default;
                $model->created_by = 0;
                $model->updated_by = 0;

                if ($model->validate(['key', 'value', 'is_active']) && $model->save(false)) {
                    \Yii::info(sprintf('Создана несуществующая настройка "%s".', $key));

                    return $model->value;
                } else {
                    foreach ($model->firstErrors as $firstError) {
                        \Yii::error(sprintf('Произошла ошибка при попытке создания настройки "%s": %s.', $key, $firstError));
                    }

                    return $default;
                }
            }

            if (!$model->is_active) {
                \Yii::warning(sprintf('Используется отключенная настройка "%s".', $key));

                return $default;
            }

            return $model->value;
        }

        /**
         * Returns a value if a key splitted by pattern
         * @param string        $key
         * @param string|null   $default
         * @param callable|null $function
         * @param string        $pattern
         * @return array
         */
        public static function valuesOf($key, $default = null, $function = null, $pattern = '/[\s+]?(,|;)[\s+]?/')
        {
            $values = preg_split($pattern, self::valueOf($key, $default));

            if (!is_null($function)) {
                if (is_callable($function)) {
                    $values = array_map($function, (array) $values);
                } else {
                    \Yii::error('Аргумент не является анонимной фукнцией и не может быть вызван.');
                }
            }

            return $values;
        }

        /**
         * Returns param value
         * @see params.php
         * @param string $param
         * @param null   $default
         * @return mixed
         */
        public static function paramOf($param, $default = null)
        {
            if (false === $value = ArrayHelper::getValue(\Yii::$app->params, $param, false)) {
                \Yii::warning(sprintf('Используется недостающая настройка из конфигурации "%s".', $param));

                return $default;
            }

            return $value;
        }

        /**
         * Returns a value of a key
         * When value is not exists, search it in params.php
         * @param string      $param
         * @param string|null $default
         * @return null|boolean|string
         */
        public static function valueOrParam($param, $default = null)
        {
            return ($setting = self::valueOf($param)) ? $setting : ArrayHelper::getValue(\Yii::$app->params, $param, $default);
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getTranslations()
        {
            return $this->hasMany(SettingsTranslation::className(), ['content_id' => 'id']);
        }
    }
