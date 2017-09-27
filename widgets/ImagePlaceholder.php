<?php

    namespace papalapa\yiistart\widgets;

    use yii\base\Widget;
    use yii\helpers\Html;

    /**
     * Class ImagePlaceholder
     * @see     http://placeholder.com/
     * @package papalapa\yiistart\widgets
     */
    class ImagePlaceholder extends Widget
    {
        public  $size          = '100x100';
        public  $width         = 100;
        public  $height        = 100;
        public  $clientOptions = [];
        private $via           = 'http://via.placeholder.com/';

        /**
         * @inheritdoc
         */
        public function init()
        {
            parent::init();

            if (!empty($this->size) && preg_match('/(\d+)x(\d+)/', $this->size, $matches)) {
                $this->width  = $matches[1];
                $this->height = $matches[2];
            }
        }

        /**
         * Insert image placeholder from url:
         * @see http://via.placeholder.com/100x100
         * @return string
         */
        public function run()
        {
            return Html::img(sprintf('%s/%dx%d', trim($this->via, '/'), $this->width, $this->height), $this->clientOptions);
        }
    }
