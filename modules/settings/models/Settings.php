<?php

    namespace papalapa\yiistart\modules\settings\models;

    use papalapa\yiistart\models\MultilingualActiveRecord;
    use papalapa\yiistart\modules\users\models\BaseUser;
    use papalapa\yiistart\validators\WhiteSpaceNormalizerValidator;
    use yii\behaviors\BlameableBehavior;
    use yii\behaviors\TimestampBehavior;
    use yii\db\Expression;
    use yii\helpers\ArrayHelper;

    /**
     * This is the model class for table "settings".
     * @property integer               $id
     * @property string                $title
     * @property string                $key
     * @property string                $value
     * @property integer               $is_active
     * @property integer               $created_by
     * @property integer               $updated_by
     * @property string                $created_at
     * @property string                $updated_at
     * @property SettingsTranslation[] $settingsTranslations
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

        public function scenarios()
        {
            return $this->localizedScenarios([
                self::SCENARIO_DEFAULT   => ['value', 'is_active'],
                self::SCENARIO_DEVELOPER => ['title', 'key', 'value', 'is_active'],
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

                [['key'], WhiteSpaceNormalizerValidator::className()],
                [['key'], 'required'],
                [['key'], 'string', 'max' => 64],
                [['key'], 'match', 'pattern' => '/^[a-z]+(\.[a-z]+)*$/'],
                [['key'], 'unique'],

                [['value'], WhiteSpaceNormalizerValidator::className()],
                [['value'], 'required'],
                [['value'], 'string'],

                [['is_active'], 'boolean'],
                [['is_active'], 'default', 'value' => 0],
            ]);
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
                return self::find()->multilingual()->where(['key' => $key])->one();
            }, ArrayHelper::getValue(\Yii::$app->params, 'cache.duration.setting', null));

            if (is_null($model)) {
                \Yii::warning(sprintf('Используется несуществующая настройка "%s".', $key));

                return $default;
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

            if (is_callable($function)) {
                $values = array_map($function, (array)$values);
            }
            else {
                \Yii::warning('Аргумент не является анонимной фукнцией и не может быть вызван.');
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
        public function getSettingsTranslations()
        {
            return $this->hasMany(SettingsTranslation::className(), ['content_id' => 'id']);
        }
    }
