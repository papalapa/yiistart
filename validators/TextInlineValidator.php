<?php

    namespace papalapa\yiistart\validators;

    use papalapa\yiistart\helpers\Stringer;
    use yii\validators\FilterValidator;

    /**
     * Class TextInlineValidator
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