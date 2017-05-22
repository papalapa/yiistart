<?php

    namespace papalapa\yiistart\widgets;

    use yii\base\InvalidConfigException;
    use yii\db\ActiveRecord;
    use yii\grid\DataColumn;

    /**
     * Class GridUserEmailColumn
     * @package papalapa\yiistart\widgets
     */
    class GridUserEmailColumn extends DataColumn
    {
        /**
         * @throws InvalidConfigException
         */
        public function init()
        {
            /**
             * @param $model ActiveRecord
             * @return null|string
             */
            $this->content = function ($model) {
                return $model->getAttribute($this->attribute)
                    ? (\Yii::$app->user->identity)::findOne(['id' => $model->getAttribute($this->attribute)])->email
                    : null;
            };

            parent::init();
        }
    }
