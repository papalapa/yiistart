<?php

    namespace papalapa\yiistart\widgets;

    use papalapa\yiistart\modules\users\models\BaseUser;
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
                    ? (($user = BaseUser::findOne(['id' => $model->getAttribute($this->attribute)])) ? $user->email : null)
                    : null;
            };

            parent::init();
        }
    }
