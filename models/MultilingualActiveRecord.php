<?php

    namespace papalapa\yiistart\models;

    use omgdef\multilingual\MultilingualBehavior;
    use papalapa\yiistart\modules\i18n\models\i18n;
    use yii\db\ActiveQuery;
    use yii\db\ActiveRecord;
    use yii\helpers\ArrayHelper;

    /**
     * Class MultilingualActiveRecord
     * @property boolean $multilingual
     * @package common\models
     */
    abstract class MultilingualActiveRecord extends ActiveRecord
    {
        /**
         * @return array
         */
        public function behaviors()
        {
            return [
                'MultilingualBehavior' => [
                    'class'            => MultilingualBehavior::className(),
                    'dynamicLangClass' => false,
                    'languages'        => i18n::locales(),
                    'defaultLanguage'  => \Yii::$app->language,
                    'langForeignKey'   => 'content_id',
                ],
            ];
        }

        /**
         * @return ActiveQuery|MultilingualActiveQuery
         */
        public static function find()
        {
            return new MultilingualActiveQuery(get_called_class());
        }

        /**
         * @param $scenarios
         * @return mixed
         */
        public function localizedScenarios($scenarios)
        {
            if ($behavior = ArrayHelper::getValue(static::behaviors(), 'MultilingualBehavior')) {
                $behaviorAttributes = $behavior['attributes'];

                foreach ($scenarios as $scenario => $attributes) {
                    foreach ($behaviorAttributes as $attribute) {
                        foreach ($this->availableLocales($behavior) as $locale) {
                            $scenarios[$scenario][] = sprintf('%s_%s', $attribute, $locale);
                        }
                    }
                }
            }

            return $scenarios;
        }

        /**
         * Для каждого перечисленного атрибута в поведении MultilingualBehavior['attributes']
         * будет добавлено описание поля модели для всех доступных локалей.
         * Локали, перечисленные в MultilingualBehavior['languages'] будут проверены со списком локалей,
         * указанных в параметрах приложения (файл params.php, атрибут 'availableLocales' => ['ru','en']).
         * ---
         * Допустим модель содержит описание: ['text' => 'Текст'].
         * Поведение содержит атрибут для интернационализации: ['text'].
         * Поведение содержит доступные локали: ['ru','en','kz'].
         * Приложение содержит доступные локали: 'availableLocales' => ['ru','kz'].
         * Тогда модель будет содержать следующие описания для полей:
         * ```php
         *      $attributeLabels = [
         *          ['text'    => 'Текст'],
         *          ['text_ru' => 'Текст (ru)'],
         *          ['text_kz' => 'Текст (kz)'],
         *      ];
         * ```
         * @param $attributes
         * @return array
         */
        public function localizedAttributes($attributes)
        {
            if ($behavior = ArrayHelper::getValue(static::behaviors(), 'MultilingualBehavior')) {
                $behaviorAttributes = $behavior['attributes'];

                foreach ($behaviorAttributes as $attribute) {
                    foreach ($this->availableLocales($behavior) as $locale) {
                        $attributes[sprintf('%s_%s', $attribute, $locale)]
                            = sprintf('%s (%s)', $attributes[$attribute], $locale);
                    }
                }
            }

            return $attributes;
        }

        /**
         * Для каждого перечисленного атрибута в поведении MultilingualBehavior['attributes']
         * будет добавлено правило валидации модели для всех доступных локалей.
         * Локали, перечисленные в MultilingualBehavior['languages'] будут проверены со списком локалей,
         * указанных в параметрах приложения (файл params.php, атрибут 'availableLocales' => ['ru','en']).
         * ---
         * Допустим модель содержит правило: [['text','name','email'],'required'].
         * Поведение содержит атрибуты для интернационализации: ['text','name'].
         * Поведение содержит доступные локали: ['ru','en','kz'].
         * Приложение содержит доступные локали: 'availableLocales' => ['ru','kz'].
         * Тогда модель будет содержать следующие правила валидации:
         * ```php
         *      $rules = [
         *          [['text','name','email'],'required'],
         *          [['text_ru'],'required'],
         *          [['text_kz'],'required'],
         *          [['name_ru'],'required'],
         *          [['name_kz'],'required'],
         *      ];
         * ```
         * @param $rules
         * @return array
         */
        public function localizedRules($rules)
        {
            if ($behavior = ArrayHelper::getValue(static::behaviors(), 'MultilingualBehavior')) {
                $behaviorAttributes = $behavior['attributes'];

                foreach ($rules as $rule) {
                    $attributes = array_shift($rule);

                    foreach ((array) $attributes as $attribute) {
                        if (in_array($attribute, $behaviorAttributes)) {
                            foreach ($this->availableLocales($behavior) as $locale) {
                                if (\Yii::$app->language <> $locale) {
                                    $validator = $rule;
                                    array_unshift($validator, sprintf('%s_%s', $attribute, $locale));
                                    $rules[] = $validator;
                                }
                            }
                        }
                    }
                }
            }

            return $rules;
        }

        /**
         * Get available locales, existing in application params[availableLocales]
         * Compare available locales with behavior languages
         * @param $multilingualBehavior
         * @return array
         */
        protected function availableLocales($multilingualBehavior)
        {
            $locales = $multilingualBehavior['languages'];
            $locales = array_values($locales) <> $locales ? array_keys($locales) : $locales;
            $locales = array_intersect(i18n::locales(), $locales);

            return $locales;
        }
    }


