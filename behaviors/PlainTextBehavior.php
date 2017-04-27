<?php

    namespace papalapa\yiistart\behaviors;

    use papalapa\yiistart\helpers\StringHelper;
    use yii\base\Behavior;
    use yii\db\ActiveQuery;
    use yii\db\ActiveRecord;

    /**
     * Class PlainTextBehavior
     * @property ActiveRecord|ActiveQuery $owner
     * @package common\behaviors
     */
    class PlainTextBehavior extends Behavior
    {
        /**
         * Attribute of AR to save plain text
         * @var string
         */
        public $attribute = 'raw';
        /**
         * List of attributes to save to plain text
         * @var array
         */
        public $attributes = [];

        /**
         * @return array
         */
        public function events()
        {
            return [
                ActiveRecord::EVENT_BEFORE_INSERT => 'plain',
                ActiveRecord::EVENT_BEFORE_UPDATE => 'plain',
            ];
        }

        /**
         * Plains the text
         */
        public function plain()
        {
            $text = [];

            foreach ($this->attributes as $attribute) {
                /* Attribute could be an array, than it will be imploded */
                $text[] = implode(' ', (array)$this->owner->getAttribute($attribute));
            }

            /* Imploding all */
            $text = implode(' ', $text);
            /* Decode html entities */
            $text = html_entity_decode($text);
            /* Stripping the tags */
            $text = strip_tags($text);
            /* Lowering characters */
            $text = mb_strtolower($text);
            /* Remove all not word-symbols */
            $text = preg_replace('/[^\w]+/u', ' ', $text);
            /* Remove vertical spaces and clean duplicates */
            $text = StringHelper::textInline($text);

            $this->owner->setAttribute($this->attribute, $text);
        }
    }
