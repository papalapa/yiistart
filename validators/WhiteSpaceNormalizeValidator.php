<?php

    namespace vendor\papalapa\yii2start\validators;

    use vendor\papalapa\yii2start\helpers\Stringer;
    use yii\validators\FilterValidator;

    /**
     * Class CleanSpacesFilter
     * @package vendor\papalapa\yii2start\validators
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