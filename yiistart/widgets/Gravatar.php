<?php

    namespace papalapa\yiistart\widgets;

    use yii;

    /**
     * Class Gravatar
     * @link    https://www.gravatar.com/
     * @package papalapa\yiistart\widgets
     */
    class Gravatar extends yii\base\Widget
    {
        /**
         * Avatar types
         */
        const TYPE_CUBE    = 'retro';
        const TYPE_FACE    = 'wavatar';
        const TYPE_MONSTER = 'monsterid';
        const TYPE_MYSTERY = 'mm';
        const TYPE_PATTERN = 'identicon';
        /**
         * String to hash
         * @var string
         */
        public $name;
        /**
         * Avatar size in pixels
         * @var int
         */
        public $size = 20;
        /**
         * Default selected type of avatar
         * @var string
         */
        public $type = self::TYPE_FACE;
        /**
         * Image html attributes
         * @var array
         */
        public $options = ['class' => 'img-rounded'];
        /**
         * API URL to find avatar
         * @var string
         */
        private $url = 'https://www.gravatar.com/avatar/';

        /**
         * @return string
         */
        public function run()
        {
            $src = sprintf($this->url.'%s?s=%d&d=%s', md5($this->name), $this->size, $this->type);

            return yii\helpers\Html::img($src, $this->options);
        }
    }