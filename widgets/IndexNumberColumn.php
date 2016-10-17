<?php

    namespace vendor\papalapa\yii2start\widgets;

    use yii\grid\DataColumn;
    use yii\helpers\Html;

    /**
     * Class IndexNumberColumn
     * @package vendor\papalapa\yii2start\widgets
     */
    class IndexNumberColumn extends DataColumn
    {
        public function init()
        {
            $this->headerOptions      = $this->headerOptions ?: ['width' => '100'];
            $this->filterInputOptions = [
                'class' => 'form-control',
                'type'  => 'number',
                'min'   => '0',
            ];
            $this->label              = $this->label ?: Html::tag('i', null, [
                'class'       => 'fa fa-sort-numeric-asc',
                'data-toggle' => 'tooltip',
                'title'       => 'Порядковый номер',
            ]);
            $this->format             = 'html';
            $this->encodeLabel        = false;
        }
    }