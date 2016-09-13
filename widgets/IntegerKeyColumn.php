<?php

    namespace papalapa\yii2\widgets;

    use yii\grid\DataColumn;

    /**
     * Class IntegerKeyColumn
     * @package vendor\papalapa\yii2\widgets
     */
    class IntegerKeyColumn extends DataColumn
    {
        public function init()
        {
            $this->headerOptions      = $this->headerOptions ?: ['width' => '100'];
            $this->filterInputOptions = [
                'class' => 'form-control',
                'type'  => 'number',
                'min'   => '1',
            ];
        }
    }