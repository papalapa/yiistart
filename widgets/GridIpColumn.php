<?php

    namespace papalapa\yiistart\widgets;

    use yii\grid\DataColumn;

    /**
     * Class GridIpColumn
     * @package papalapa\yiistart\widgets
     */
    class GridIpColumn extends DataColumn
    {
        /**
         * @var array
         */
        public $headerOptions = ['width' => '150'];

        /**
         * @inheritdoc
         */
        public function init()
        {
            $this->content = function ($model, $key, $index, $column) {
                return $model->{$this->attribute} ? long2ip($model->{$this->attribute}) : null;
            };
        }
    }
