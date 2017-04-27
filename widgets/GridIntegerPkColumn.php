<?php

    namespace papalapa\yiistart\widgets;

    use yii\grid\DataColumn;

    /**
     * Class GridIntegerColumn
     * @package papalapa\yiistart\widgets
     */
    class GridIntegerPkColumn extends DataColumn
    {
        /**
         * @var array
         */
        public $headerOptions = ['width' => '100'];
        /**
         * @var array
         */
        public $filterInputOptions = ['class' => 'form-control', 'type' => 'number', 'min' => '1'];
    }
