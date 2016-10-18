<?php

    namespace papalapa\yii2start\validators;

    use papalapa\yii2start\helpers\Stringer;
    use yii\validators\FilterValidator;

    /**
     * Class WhiteSpaceNormalizeValidator
     * @package papalapa\yii2start\validators
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