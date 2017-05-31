<?php

    namespace papalapa\yiistart\widgets;

    use yii\grid\DataColumn;

    /**
     * Class GridTextColumn
     * @package papalapa\yiistart\widgets
     */
    class GridTextColumn extends DataColumn
    {
        /**
         * @var integer
         */
        public $maxWidth = 500;
        /**
         * @var integer
         */
        public $minWidth = 400;

        /**
         * @inheritdoc
         */
        public function init()
        {
            $this->contentOptions = [
                'style' => [
                    'width'       => 'auto',
                    'min-width'   => sprintf('%dpx', $this->minWidth),
                    'max-width'   => sprintf('%dpx', $this->maxWidth),
                    'word-wrap'   => 'break-word',
                    'white-space' => 'normal',
                ],
            ];

            parent::init();
        }
    }
