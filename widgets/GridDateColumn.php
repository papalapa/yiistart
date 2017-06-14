<?php

    namespace papalapa\yiistart\widgets;

    use yii\db\ActiveRecord;
    use yii\grid\DataColumn;

    /**
     * Class GridDateColumn
     * @package papalapa\yiistart\widgets
     */
    class GridDateColumn extends DataColumn
    {
        /**
         * @var string
         */
        public $dateFormat = 'd MMMM YYYY, HH:mm';
        /**
         * @var
         */
        public $model;

        /**
         * @inheritdoc
         */
        public function init()
        {
            /**
             * @param $model ActiveRecord
             * @return null|string
             */
            $this->content = function ($model) {
                return $model->getAttribute($this->attribute)
                    ? \Yii::$app->formatter->asDate($model->getAttribute($this->attribute), $this->dateFormat)
                    : null;
            };

            $this->filter = DateTimePicker::widget([
                'model'     => $this->model,
                'attribute' => $this->attribute,
            ]);

            parent::init();
        }
    }
