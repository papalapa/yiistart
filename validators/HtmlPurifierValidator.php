<?php

    namespace papalapa\yiistart\validators;

    use yii\base\InvalidConfigException;
    use yii\helpers\HtmlPurifier;
    use yii\validators\FilterValidator;

    /**
     * Class HtmlPurifierValidator
     * @package papalapa\yiistart\validators
     */
    class HtmlPurifierValidator extends FilterValidator
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
