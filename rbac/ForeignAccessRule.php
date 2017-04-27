<?php

    namespace papalapa\yiistart\rbac;

    use papalapa\yiistart\models\BaseUser;
    use yii;
    use yii\helpers\ArrayHelper;
    use yii\rbac\Item;
    use yii\rbac\Rule;

    /**
     * Class ForeignAccessRule
     * @package common\rbac
     */
    class ForeignAccessRule extends Rule
    {
        /**
         * Rule name
         * @var string
         */
        public $name = 'foreignAccess';
        /**
         * Attribute to check
         * @var string
         */
        private $attribute = 'created_by';

        /**
         * Params conditions:
         * 1. $model ActiveRecord
         * 2. ['model' => ActiveRecord $model]
         * 3. ['created_by' => int $value]
         * @param int|string                $user
         * @param Item                      $item
         * @param array|yii\db\ActiveRecord $params
         * @return bool
         */
        public function execute($user, $item, $params)
        {
            // простые пользователи ниже менеджера проходят мимо
            if (BaseUser::isGuest() || BaseUser::identity()->role < BaseUser::ROLE_MANAGER) {
                return false;
            }

            if ($params instanceof yii\db\ActiveRecord) {
                $model = $params;
            } elseif (is_array($params)) {
                if (ArrayHelper::keyExists('model', $params)) {
                    $model = ArrayHelper::getValue($params, 'model');
                } elseif (ArrayHelper::keyExists($this->attribute, $params)) {
                    $attribute = ArrayHelper::getValue($params, $this->attribute);
                }
            }

            if (isset($model)) {
                $attribute = $model->getAttribute($this->attribute);
            }

            if (isset($attribute)) {
                $owner = BaseUser::findOne(['id' => $attribute]);

                // владелец контента должен быть ниже текущего пользователя, либо текущий пользователь должен быть выше менеджера
                return $owner->role < BaseUser::identity()->role || BaseUser::identity()->role > BaseUser::ROLE_MANAGER;
            }

            // если не указан атрибут владельца, то контент могут изменить администратор или девелопер
            return BaseUser::identity()->role > BaseUser::ROLE_MANAGER;
        }
    }
