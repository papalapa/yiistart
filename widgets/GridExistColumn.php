<?php

    namespace papalapa\yiistart\widgets;

    use yii\grid\DataColumn;
    use yii\helpers\Html;

    /**
     * Class GridExistColumn
     * @package papalapa\yiistart\widgets
     */
    class GridExistColumn extends DataColumn
    {
        /**
         * @var string
         */
        public $format = 'html';
        /**
         * @var array
         */
        public $filter = [0 => 'нет', 1 => 'есть'];

        /**
         * @inheritdoc
         */
        public function init()
        {
            $this->content = function ($model) /* @var $model \yii\db\ActiveRecord */ {
                return Html::tag('i', null, ['class' => 'fa fa-fw '.($model->{$this->attribute} ? 'fa-check text-success' : 'fa-times text-danger')]);
            };
        }
    }
