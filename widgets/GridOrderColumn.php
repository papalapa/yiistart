<?php

    namespace papalapa\yiistart\widgets;

    use yii\grid\DataColumn;

    /**
     * Class GridOrderColumn
     * @package papalapa\yiistart\widgets
     */
    class GridOrderColumn extends DataColumn
    {
        /**
         * @var array
         */
        public $headerOptions = ['width' => '100'];
        /**
         * @var string
         */
        public $format = 'html';
        /**
         * @var bool
         */
        public $encodeLabel = false;
        /**
         * @var string
         */
        public $label = '<i class="fa fa-sort-numeric-asc" data-toggle="tooltip" title="Порядковый номер"></i>';
        /**
         * @var array
         */
        public $filterInputOptions = ['class' => 'form-control', 'type' => 'number', 'min' => '0'];
    }
