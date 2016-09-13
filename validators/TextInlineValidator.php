<?php

    namespace vendor\papalapa\yii2\validators;

    use vendor\papalapa\yii2\helpers\Stringer;
    use yii\validators\FilterValidator;

    /**
     * Class SpaceLessFilter
     * @package vendor\papalapa\yii2\validators
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