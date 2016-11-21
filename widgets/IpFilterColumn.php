<?php

    namespace papalapa\yiistart\widgets;

    use yii\grid\DataColumn;

    /**
     * Class IpFilterColumn
     * @package papalapa\yiistart\widgets
     */
    class IpFilterColumn extends DataColumn
    {
        public function init()
        {
            $this->headerOptions = ['width' => '150'];
            $this->value         = function ($model, $key, $index, $column) {
                return long2ip($model->ip);
            };
        }
    }