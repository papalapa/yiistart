<?php

    namespace vendor\papalapa\yii2\widgets;

    use yii;

    /**
     * Class Gravatar
     * @link https://www.gravatar.com/
     * @package vendor\papalapa\yii2\widgets
     */
    class Gravatar extends yii\base\Widget
    {
        /**
         * Types of gravatars
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
         * Size in pixels
         * @var int
         */
        public $size = 20;
        /**
         * Selected type of gravatar
         * @var string
         */
        public $type = self::TYPE_FACE;
        /**
         * Image html options
         * @var array
         */
        public $options = ['class' => 'img-rounded'];
        /**
         * URL to find gravatar
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