<?php

    namespace vendor\papalapa\yii2\validators;

    use vendor\papalapa\yii2\helpers\Stringer;
    use yii\validators\FilterValidator;

    /**
     * Class CleanSpacesFilter
     * @package vendor\papalapa\yii2\validators
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