<?php

    namespace papalapa\yiistart\modules\i18n\models;

    use papalapa\yiistart\modules\settings\models\Settings;
    use yii\base\InvalidParamException;

    /**
     * Class i18n
     * @package papalapa\yiistart\modules\i18n\models
     */
    class i18n
    {
        /**
         * @return array|mixed
         */
        public static function locales()
        {
            if (false === $locales = Settings::paramOf('i18n.locales.available', false)) {
                throw new InvalidParamException("Need to set available locales in 'params.php' file: ['ru','en'] or ['ru' => 'Ru', 'en' => 'En']");
            }

            if (array_values($locales) !== $locales) {
                $locales = array_keys($locales);
            }

            return $locales;
        }
    }
