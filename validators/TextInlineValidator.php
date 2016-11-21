<?php

    namespace papalapa\yiistart\validators;

    use papalapa\yiistart\helpers\Stringer;
    use yii\validators\FilterValidator;

    /**
     * Class TextInlineValidator
     * Validator converts all multiple horizontal and vertical spaces (\t, \r, \n ...) to one inline space ' '
     * @package papalapa\yiistart\validators
     */
    class TextInlineValidator extends FilterValidator
    {
        public function init()
        {
            $this->filter = function ($string) {
                return Stringer::textInline($string);
            };
            parent::init();
        }
    }