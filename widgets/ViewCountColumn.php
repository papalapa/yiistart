<?php

    namespace vendor\papalapa\yii2\widgets;

    use yii\grid\DataColumn;
    use yii\helpers\Html;

    /**
     * Class ViewCountColumn
     * @package vendor\papalapa\yii2\widgets
     */
    class ViewCountColumn extends DataColumn
    {
        public function init()
        {
            $this->headerOptions = $this->headerOptions ?: ['width' => '50'];
            $this->label         = $this->label ?: Html::tag('i', null, [
                'class'       => 'fa fa-eye',
                'data-toggle' => 'tooltip',
                'title'       => 'Количество просмотров',
            ]);
            $this->encodeLabel   = false;
            $this->filter        = false;
        }
    }