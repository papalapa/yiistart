<?php

    namespace papalapa\yiistart\validators;

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
                return trim(preg_replace('~(\h|\v)+~u', ' ', (string)$string));
            };

            parent::init();
        }
    }
