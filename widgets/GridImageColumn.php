<?php

    namespace papalapa\yiistart\widgets;

    use yii\db\ActiveRecord;
    use yii\grid\DataColumn;
    use yii\helpers\Html;

    /**
     * Class GridImageColumn
     * @package papalapa\yiistart\widgets
     */
    class GridImageColumn extends DataColumn
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
            $this->content = function ($model) /* @var $model ActiveRecord */ {
                return $model->hasAttribute($this->attribute) && $model->getAttribute($this->attribute)
                    ? Html::a(Html::img($model->getAttribute($this->attribute), ['width' => 150]),
                        $model->getAttribute($this->attribute), ['target' => '_blank', 'data-pjax' => 0]
                    )
                    : null;
            };
        }
    }
