<?php

    namespace papalapa\yiistart\behaviors;

    use papalapa\yiistart\helpers\FileHelper;
    use papalapa\yiistart\models\BelongingImages;
    use papalapa\yiistart\models\ContainingActiveRecord;
    use papalapa\yiistart\traits\BelongingImagesTrait;
    use yii\base\InvalidConfigException;
    use yii\base\InvalidParamException;

    /**
     * Class BelongingImagesBehavior
     * @property ContainingActiveRecord|BelongingImagesTrait $owner
     * @package papalapa\yiistart\behaviors
     */
    class BelongingImagesBehavior extends BelongingBehavior
    {
        /**
         * ActiveRecord attribute name to collect items
         * @var string
         */
        public $attribute = '_images';
        /**
         * Regular expression for images
         * @var string
         */
        public $pattern = '~^/[a-z]+/[a-z0-9_]+\.(gif|png|jpe?g)$~ui';
        /**
         * Attribute in BaseBelonging
         * @var string
         */
        public $belongingAttribute = 'path';

        /**
         * @throws \yii\base\InvalidConfigException
         */
        public function init()
        {
            if (!isset($this->belongingClass)) {
                $this->belongingClass = BelongingImages::className();
            }

            if (!is_string($this->belongingClass) || $this->belongingClass === '' || !((new $this->belongingClass) instanceof BelongingImages)) {
                throw new InvalidConfigException('Property "belongingClass" must be an active record class extended from BelongingImages.');
            }

            parent::init();
        }

        /**
         * @return null
         */
        protected function checkConsistency()
        {
            if ($this->minCount && count($this->owner->{$this->attribute}) < $this->minCount) {
                $this->owner->addError($this->attribute, 'Необходимо загрузить недостающие изображения');
            }

            if ($this->maxCount && count($this->owner->{$this->attribute}) > $this->maxCount) {
                $this->owner->addError($this->attribute, 'Превышено максимальное количество изображений');
            }

            return null;
        }

        /**
         * @param $item
         * @return mixed
         */
        protected function prepareItem($item)
        {
            if ($this->pattern) {
                $result = preg_match($this->pattern, $item);
                if (false === $result) {
                    throw new InvalidParamException(sprintf('Using incorrect regular expression pattern "%s".', $this->pattern));
                } elseif ($result === 0) {
                    $item = null;
                }
            }

            $path = \Yii::getAlias('@frontend/web'.$item);

            if (!FileHelper::exists($path) || !FileHelper::is_file($path)) {
                $item = null;
            }

            return $item;
        }
    }
