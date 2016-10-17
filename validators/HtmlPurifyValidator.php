<?php

    namespace vendor\papalapa\yii2start\validators;

    use yii\base\InvalidConfigException;
    use yii\helpers\HtmlPurifier;
    use yii\validators\FilterValidator;

    /**
     * Class HtmlPurifyValidator
     * @package vendor\papalapa\yii2start\validators
     */
    class HtmlPurifyValidator extends FilterValidator
    {
        public $options = [];

        public function init()
        {
            if (!is_array($this->options)) {
                throw new InvalidConfigException('Options property must be an array.');
            }

            $this->filter = function ($string) {
                return HtmlPurifier::process($string, $this->options);
            };

            parent::init();
        }
    }