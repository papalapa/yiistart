<?php

    namespace papalapa\yiistart\widgets;

    use yii\grid\DataColumn;
    use yii\helpers\Html;

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
        public $minWidth = 300;

        /**
         * @param mixed $model
         * @param mixed $key
         * @param int   $index
         * @return string
         */
        protected function renderDataCellContent($model, $key, $index)
        {
            $options = [
                'style' => [
                    'width'       => 'auto',
                    'min-width'   => sprintf('%dpx', $this->minWidth),
                    'max-width'   => sprintf('%dpx', $this->maxWidth),
                    'word-wrap'   => 'break-word',
                    'white-space' => 'normal',
                ],
            ];

            return Html::tag('div', parent::renderDataCellContent($model, $key, $index), $options);
        }
    }
