<?php

    namespace vendor\papalapa\yii2\widgets;

    use yii\grid\DataColumn;

    /**
     * Class IpFilterColumn
     * @package vendor\papalapa\yii2\widgets
     */
    class IpFilterColumn extends DataColumn
    {
        public function init()
        {
            $this->headerOptions = $this->headerOptions ?: ['width' => '150'];
            $this->value         = $this->value ?: function ($model, $key, $index, $column) {
                return long2ip($model->ip);
            };
        }
    }