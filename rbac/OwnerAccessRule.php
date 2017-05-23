<?php

    namespace papalapa\yiistart\rbac;

    use papalapa\yiistart\modules\users\models\BaseUser;
    use yii;
    use yii\helpers\ArrayHelper;
    use yii\rbac\Item;
    use yii\rbac\Rule;

    /**
     * Class OwnerAccessRule
     * @package papalapa\yiistart\rbac
     */
    class OwnerAccessRule extends Rule
    {
        /**
         * @var string
         */
        public $name = 'ownerAccess';
        /**
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
            // простые пользователи ниже автора проходят мимо
            if (Yii::$app->user->isGuest || Yii::$app->user->identity->role < BaseUser::ROLE_AUTHOR) {
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

            // если не указан атрибут владельца, то контент могут изменить администратор или девелопер
            return (!isset($attribute) && Yii::$app->user->identity->role > BaseUser::ROLE_MANAGER)
                   // иначе, атрибут владельца должен совпадать
                   || strcmp(Yii::$app->user->identity->id, $attribute) === 0;
        }
    }
