<?php

    namespace papalapa\yiistart\rbac;

    use papalapa\yiistart\models\User;
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
            $role = Yii::$app->user->isGuest ? User::ROLE_GUEST : User::identity()->role;

            if ($item->name === 'developer') {
                return $role == User::ROLE_DEVELOPER;
            } elseif ($item->name === 'admin') {
                return $role >= User::ROLE_ADMIN;
            } elseif ($item->name === 'manager') {
                return $role == User::ROLE_MANAGER || $role >= User::ROLE_ADMIN;
            } elseif ($item->name === 'author') {
                return $role == User::ROLE_AUTHOR || $role >= User::ROLE_ADMIN;
            } elseif ($item->name === 'user') {
                return $role == User::ROLE_USER || $role >= User::ROLE_ADMIN;
            } elseif ($item->name === 'guest') {
                return $role == User::ROLE_GUEST || $role >= User::ROLE_ADMIN;
            }

            return false;
        }
    }
