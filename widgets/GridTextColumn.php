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
         * @var string
         */
        public $maxWidth = 200;

        /**
         * @inheritdoc
         */
        public function init()
        {
            $this->contentOptions = [
                'style' => [
                    'max-width'   => sprintf('%dpx', $this->maxWidth),
                    'word-wrap'   => 'break-word',
                    'white-space' => 'normal',
                ],
            ];

            parent::init();
        }
    }
