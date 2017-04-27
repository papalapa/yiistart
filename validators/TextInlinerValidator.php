<?php

    namespace papalapa\yiistart\validators;

    use papalapa\yiistart\helpers\StringHelper;
    use yii\validators\FilterValidator;

    /**
     * Class TextInlinerValidator
     * Validator converts all multiple horizontal and vertical spaces (\t, \r, \n ...) to one inline space ' '
     * @package papalapa\yiistart\validators
     */
    class TextInlinerValidator extends FilterValidator
    {
        public function init()
        {
            $this->filter = function ($string) {
                return StringHelper::textInline($string);
            };

            parent::init();
        }
    }
