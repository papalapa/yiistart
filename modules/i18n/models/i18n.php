<?php

    namespace papalapa\yiistart\modules\i18n\models;

    use yii\base\InvalidConfigException;
    use yii\base\InvalidParamException;
    use yii\helpers\ArrayHelper;

    /**
     * Class i18n
     * @package papalapa\yiistart\modules\i18n\models
     */
    class i18n
    {
        /**
         * @return array|mixed
         * @throws InvalidConfigException|InvalidParamException
         */
        public static function locales()
        {
            if (false === $locales = ArrayHelper::getValue(\Yii::$app->params, 'i18n.locales.available', false)) {
                throw new InvalidParamException("Need to set available locales in 'params.php' file: ['ru','en'] or ['ru' => 'Ru', 'en' => 'En']");
            }

            if (array_values($locales) !== $locales) {
                $locales = array_keys($locales);
            }

            if (!in_array(\Yii::$app->language, $locales)) {
                throw new InvalidConfigException(sprintf("Only '%s' languages allowed in 'param.php' of your app, not '%s'.", implode(', ', $locales), \Yii::$app->language));
            }

            return $locales;
        }
    }
