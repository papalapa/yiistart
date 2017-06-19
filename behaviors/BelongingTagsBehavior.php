<?php

    namespace papalapa\yiistart\behaviors;

    use papalapa\yiistart\models\BelongingTags;
    use papalapa\yiistart\models\ContainingActiveRecord;
    use papalapa\yiistart\traits\BelongingTagsTrait;
    use yii\base\InvalidConfigException;
    use yii\base\InvalidParamException;

    /**
     * Class BelongingTagsBehavior
     * @property ContainingActiveRecord|BelongingTagsTrait $owner
     * @package papalapa\yiistart\behaviors
     */
    class BelongingTagsBehavior extends BelongingBehavior
    {
        /**
         * ActiveRecord attribute name to collect items
         * @var string
         */
        public $attribute = '_tags';
        /**
         * Regular expression for tags
         * @var string
         */
        public $pattern = '/^([a-zа-я0-9әіңғүұқөһ](-| )?)*[a-zа-я0-9әіңғүұқөһ]+$/ui';
        /**
         * Max length for each tag
         * @var int
         */
        public $maxLength = 128;
        /**
         * Attribute in BaseBelonging
         * @var string
         */
        public $belongingAttribute = 'tag';

        /**
         * @throws \yii\base\InvalidConfigException
         */
        public function init()
        {
            if (!isset($this->belongingClass)) {
                $this->belongingClass = BelongingTags::className();
            }

            if (!is_string($this->belongingClass) || $this->belongingClass === '' || !((new $this->belongingClass) instanceof BelongingTags)) {
                throw new InvalidConfigException('Property "belongingClass" must be an active record class extended from BelongingTags.');
            }

            if (!is_int($this->maxLength) && !ctype_digit($this->maxLength)) {
                throw new InvalidParamException('Attribute "maxLength" must be an positive integer value.');
            }

            parent::init();
        }

        /**
         * @return null
         */
        protected function checkConsistency()
        {
            if ($this->minCount && count($this->owner->{$this->attribute}) < $this->minCount) {
                $this->owner->addError($this->attribute, 'Необходимо указать недостающие теги');
            }

            if ($this->maxCount && count($this->owner->{$this->attribute}) > $this->maxCount) {
                $this->owner->addError($this->attribute, 'Превышено максимальное количество тегов');
            }

            return null;
        }

        /**
         * Preparing each tag
         * @param $item
         * @return mixed
         */
        protected function prepareItem($item)
        {
            /* Lowering tag */
            $item = mb_strtolower($item);

            /* Check max length of tag if it is non-zero integer value */
            if ($this->maxLength && mb_strlen($item) > $this->maxLength) {
                $item = null;
            }

            /* Matching tag by pattern */
            if ($this->pattern) {
                $result = preg_match($this->pattern, $item);
                if (false === $result) {
                    throw new InvalidParamException(sprintf('Using incorrect regular expression pattern "%s".', $this->pattern));
                } elseif ($result === 0) {
                    $item = null;
                }
            }

            return $item;
        }
    }
