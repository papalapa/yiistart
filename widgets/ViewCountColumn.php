<?php

    namespace papalapa\yiistart\widgets;

    use yii\grid\DataColumn;
    use yii\helpers\Html;

    /**
     * Class ViewCountColumn
     * @package papalapa\yiistart\widgets
     */
    class ViewCountColumn extends DataColumn
    {
        public function init()
        {
            $this->headerOptions = [
                'width' => '50',
            ];
            $this->label         = Html::tag('i', null, [
                'class'       => 'fa fa-eye',
                'data-toggle' => 'tooltip',
                'title'       => 'Количество просмотров',
            ]);
            $this->encodeLabel   = false;
            $this->filter        = false;
        }
    }