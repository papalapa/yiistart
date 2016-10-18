<?php

    namespace papalapa\yii2start\validators;

    use papalapa\yii2start\helpers\Stringer;
    use yii\validators\FilterValidator;

    /**
     * Class TextInlineValidator
     * @package papalapa\yii2start\validators
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