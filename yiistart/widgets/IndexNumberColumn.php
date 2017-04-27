<?php

    namespace papalapa\yiistart\widgets;

    use yii\grid\DataColumn;
    use yii\helpers\Html;

    /**
     * Class IndexNumberColumn
     * @package papalapa\yiistart\widgets
     */
    class IndexNumberColumn extends DataColumn
    {
        public function init()
        {
            $this->headerOptions      = [
                'width' => '100',
            ];
            $this->filterInputOptions = [
                'class' => 'form-control',
                'type'  => 'number',
                'min'   => '0',
            ];
            $this->label              = Html::tag('i', null, [
                'class'       => 'fa fa-sort-numeric-asc',
                'data-toggle' => 'tooltip',
                'title'       => 'Порядковый номер',
            ]);
            $this->format             = 'html';
            $this->encodeLabel        = false;
        }
    }