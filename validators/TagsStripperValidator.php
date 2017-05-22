<?php

    namespace papalapa\yiistart\validators;

    use yii\validators\FilterValidator;

    /**
     * Class TagsStripperValidator
     * Stripping tags
     * @package papalapa\yiistart\validators
     */
    class TagsStripperValidator extends FilterValidator
    {
        public function init()
        {
            $this->filter = function ($string) {
                return strip_tags(html_entity_decode($string));
            };

            parent::init();
        }
    }
