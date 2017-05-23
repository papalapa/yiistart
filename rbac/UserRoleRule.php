<?php

    namespace papalapa\yiistart\rbac;

    use papalapa\yiistart\modules\users\models\BaseUser;
    use yii;
    use yii\rbac\Item;
    use yii\rbac\Rule;

    /**
     * Class UserRoleRule
     * @package papalapa\yiistart\rbac
     */
    class UserRoleRule extends Rule
    {
        /**
         * @var string
         */
        public $name = 'userRoleRule';

        /**
         * Executes the rule.
         * @param string|integer $user   the user ID. This should be either an integer or a string representing
         *                               the unique identifier of a user. See [[\yii\web\User::id]].
         * @param Item           $item   the role or permission that this rule is associated with
         * @param array          $params parameters passed to [[ManagerInterface::checkAccess()]].
         * @return boolean a value indicating whether the rule permits the auth item it is associated with.
         */
        public function execute($user, $item, $params)
        {
            $role = Yii::$app->user->isGuest ? BaseUser::ROLE_GUEST : Yii::$app->user->identity->role;

            if ($item->name === 'developer') {
                return $role == BaseUser::ROLE_DEVELOPER;
            } elseif ($item->name === 'admin') {
                return $role >= BaseUser::ROLE_ADMIN;
            } elseif ($item->name === 'manager') {
                return $role == BaseUser::ROLE_MANAGER || $role >= BaseUser::ROLE_ADMIN;
            } elseif ($item->name === 'author') {
                return $role == BaseUser::ROLE_AUTHOR || $role >= BaseUser::ROLE_ADMIN;
            } elseif ($item->name === 'user') {
                return $role == BaseUser::ROLE_USER || $role >= BaseUser::ROLE_ADMIN;
            } elseif ($item->name === 'guest') {
                return $role == BaseUser::ROLE_GUEST || $role >= BaseUser::ROLE_ADMIN;
            }

            return false;
        }
    }
