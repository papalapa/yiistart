<?php

    namespace papalapa\yiistart\widgets;

    use yii\grid\DataColumn;

    /**
     * Class GridIntegerColumn
     * @package papalapa\yiistart\widgets
     */
    class GridIntegerColumn extends DataColumn
    {
        /**
         * @var array
         */
        public $filterInputOptions = ['class' => 'form-control', 'type' => 'number', 'min' => '1'];
    }
