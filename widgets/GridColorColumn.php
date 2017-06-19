<?php

    namespace papalapa\yiistart\widgets;

    use kartik\color\ColorInput;
    use yii\grid\DataColumn;
    use yii\helpers\Html;

    /**
     * Class GridColorColumn
     * @package papalapa\yiistart\widgets
     */
    class GridColorColumn extends DataColumn
    {
        public $tag  = 'div';
        public $size = '1em';
        public $model;

        /**
         * @inheritdoc
         */
        public function init()
        {
            $this->content = function ($model) /* @var $model \yii\db\ActiveRecord */ {
                return !$model->getAttribute($this->attribute)
                    ? null
                    : Html::tag($this->tag, null, [
                        'style' => [
                            'display'          => 'inline-block',
                            'width'            => $this->size,
                            'height'           => $this->size,
                            'border-radius'    => $this->size,
                            'background-color' => $model->getAttribute($this->attribute),
                        ],
                    ]).' '.Html::tag('b', $model->getAttribute($this->attribute), ['style' => ['color' => $model->getAttribute($this->attribute)]]);
            };

            $this->contentOptions = ['style' => ['text-align' => 'center']];

            $this->filter = ColorInput::widget([
                'model'     => $this->model,
                'attribute' => $this->attribute,
            ]);

            parent::init();
        }
    }
