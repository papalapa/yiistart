<?php

    namespace papalapa\yiistart\widgets;

    use yii\grid\DataColumn;

    /**
     * Class GridViewCountColumn
     * @package papalapa\yiistart\widgets
     */
    class GridViewCountColumn extends DataColumn
    {
        /**
         * @var array
         */
        public $headerOptions = ['width' => '50'];
        /**
         * @var string
         */
        public $label = '<i class="fa fa-eye" data-toggle="tooltip" title="Кол-во просмотров"></i>';
        /**
         * @var bool
         */
        public $encodeLabel = false;
        /**
         * @var bool
         */
        public $filter = false;
    }
