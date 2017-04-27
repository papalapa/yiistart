<?php

    namespace papalapa\yiistart\validators;

    use papalapa\yiistart\helpers\StringHelper;
    use yii\validators\FilterValidator;

    /**
     * Class WhiteSpaceNormalizerValidator
     * Validator cleaning string by removing multi spaces, spaces in start of line or end of line
     * @package papalapa\yiistart\validators
     */
    class WhiteSpaceNormalizerValidator extends FilterValidator
    {
        public function init()
        {
            $this->filter = function ($string) {
                return StringHelper::whiteSpaceNormalize($string);
            };

            parent::init();
        }
    }
