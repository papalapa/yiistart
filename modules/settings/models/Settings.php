<?php

    namespace papalapa\yiistart\modules\settings\models;

    use papalapa\yiistart\models\MultilingualActiveRecord;
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
     * @property string                $value
     * @property string                $comment
     * @property integer               $is_active
     * @property integer               $created_by
     * @property integer               $updated_by
     * @property string                $created_at
     * @property string                $updated_at
     * @property SettingsTranslation[] $translations
     */
    class Settings extends MultilingualActiveRecord
    {
        const SCENARIO_DEVELOPER = 'developer';

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
                'id'         => 'ID',
                'title'      => 'Название',
                'key'        => 'Ключ',
                'value'      => 'Значение',
                'comment'    => 'Комментарий',
                'is_active'  => 'Активность',
                'created_by' => 'Кем создано',
                'updated_by' => 'Кем изменено',
                'created_at' => 'Дата создания',
                'updated_at' => 'Дата изменения',
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
                self::SCENARIO_DEVELOPER => ['title', 'key', 'value', 'comment', 'is_active'],
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
                [['key'], 'match', 'pattern' => '/^[a-z]+(\.[a-z]+)*$/'],
                [['key'], 'unique'],

                [['value'], WhiteSpaceNormalizerValidator::className()],
                [['value'], 'required', 'on' => [self::SCENARIO_DEFAULT]],
                [['value'], 'string'],

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
