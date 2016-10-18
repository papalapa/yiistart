<?php

    namespace papalapa\yiistart\validators;

    use papalapa\yiistart\helpers\Stringer;
    use yii\validators\FilterValidator;

    /**
     * Class WhiteSpaceNormalizeValidator
     * @package papalapa\yiistart\validators
     */
    class WhiteSpaceNormalizeValidator extends FilterValidator
    {
        public function init()
        {
            $this->filter = function ($string) {
                return Stringer::whiteSpaceNormalize($string);
            };
            parent::init();
        }
    }