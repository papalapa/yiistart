<?php

    namespace papalapa\yiistart\controllers;

    use papalapa\yiistart\rbac\ForeignAccessRule;
    use papalapa\yiistart\rbac\OwnerAccessRule;
    use papalapa\yiistart\rbac\UserRoleRule;
    use yii\rbac\ManagerInterface;

    /**
     * Class RbacController
     * @package papalapa\yiistart\controllers
     */
    class RbacController extends \yii\console\Controller
    {
        /**
         * @var ManagerInterface
         */
        protected $authManager;

        /**
         * @inheritdoc
         */
        public function actionInit()
        {
            $this->authManager = \Yii::$app->authManager;
            $this->authManager->removeAll();
            echo 'Old rules has been removed.' . PHP_EOL;

            $this->createPermissions();
        }

        /**
         * Making rules to editing content
         * There are five permissions:
         * - create content
         * - view content (attribute is_active)
         * - listing content (attribute is_active)
         * - update content
         * - delete content
         */
        protected function createPermissions()
        {
            $userRoleRule = new UserRoleRule();
            $this->authManager->add($userRoleRule);

            $guest     = $this->authManager->createRole('guest');
            $user      = $this->authManager->createRole('user');
            $author    = $this->authManager->createRole('author');
            $manager   = $this->authManager->createRole('manager');
            $admin     = $this->authManager->createRole('admin');
            $developer = $this->authManager->createRole('developer');

            echo 'User roles has been created.' . PHP_EOL;

            $guest->ruleName     = $userRoleRule->name;
            $user->ruleName      = $userRoleRule->name;
            $author->ruleName    = $userRoleRule->name;
            $manager->ruleName   = $userRoleRule->name;
            $admin->ruleName     = $userRoleRule->name;
            $developer->ruleName = $userRoleRule->name;

            $this->authManager->add($guest);
            $this->authManager->add($user);
            $this->authManager->add($author);
            $this->authManager->add($manager);
            $this->authManager->add($admin);
            $this->authManager->add($developer);

            echo 'User roles has been added.' . PHP_EOL;

            // ------------------------------------------------------------------------------

            /** Управление смоим и чужим контентом */
            $ownerPermissions   = $this->createOwnerAccess();
            $foreignPermissions = $this->createForeignAccess();

            // ------------------------------------------------------------------------------

            /** Управление пользователями */
            $createUser              = $this->authManager->createPermission('createUser');
            $createUser->description = 'Создание пользователей';
            $viewUser                = $this->authManager->createPermission('viewUser');
            $viewUser->description   = 'Просмотр пользователей';
            $indexUser               = $this->authManager->createPermission('indexUser');
            $indexUser->description  = 'Листинг пользователей';
            $updateUser              = $this->authManager->createPermission('updateUser');
            $updateUser->description = 'Изменение пользователей';
            $deleteUser              = $this->authManager->createPermission('deleteUser');
            $deleteUser->description = 'Удаление пользователей';

            /** Управление переводами */
            $createTranslation              = $this->authManager->createPermission('createTranslation');
            $createTranslation->description = 'Создание переводов';
            $viewTranslation                = $this->authManager->createPermission('viewTranslation');
            $viewTranslation->description   = 'Просмотр переводов';
            $indexTranslation               = $this->authManager->createPermission('indexTranslation');
            $indexTranslation->description  = 'Листинг переводов';
            $updateTranslation              = $this->authManager->createPermission('updateTranslation');
            $updateTranslation->description = 'Изменение переводов';
            $deleteTranslation              = $this->authManager->createPermission('deleteTranslation');
            $deleteTranslation->description = 'Удаление переводов';

            /** Управление настройками */
            $createSetting              = $this->authManager->createPermission('createSetting');
            $createSetting->description = 'Создание настроек';
            $viewSetting                = $this->authManager->createPermission('viewSetting');
            $viewSetting->description   = 'Просмотр настроек';
            $indexSetting               = $this->authManager->createPermission('indexSetting');
            $indexSetting->description  = 'Листинг настроек';
            $updateSetting              = $this->authManager->createPermission('updateSetting');
            $updateSetting->description = 'Изменение настроек';
            $deleteSetting              = $this->authManager->createPermission('deleteSetting');
            $deleteSetting->description = 'Удаление настроек';

            echo 'New permissions has been created.' . PHP_EOL;

            // ------------------------------------------------------------------------------

            /** Регистрация правил */
            $this->authManager->add($createUser);
            $this->authManager->add($viewUser);
            $this->authManager->add($indexUser);
            $this->authManager->add($updateUser);
            $this->authManager->add($deleteUser);

            $this->authManager->add($createTranslation);
            $this->authManager->add($viewTranslation);
            $this->authManager->add($indexTranslation);
            $this->authManager->add($updateTranslation);
            $this->authManager->add($deleteTranslation);

            $this->authManager->add($createSetting);
            $this->authManager->add($viewSetting);
            $this->authManager->add($indexSetting);
            $this->authManager->add($updateSetting);
            $this->authManager->add($deleteSetting);

            echo 'New permissions has been added.' . PHP_EOL;

            // ------------------------------------------------------------------------------

            /** Добавление правил */
            $this->authManager->addChild($admin, $ownerPermissions);
            $this->authManager->addChild($admin, $foreignPermissions);

            $this->authManager->addChild($admin, $createUser);
            $this->authManager->addChild($admin, $viewUser);
            $this->authManager->addChild($admin, $indexUser);
            $this->authManager->addChild($admin, $updateUser);
            $this->authManager->addChild($admin, $deleteUser);

            // $this->authManager->addChild($admin, $createTranslation); // translations create automatic
            $this->authManager->addChild($admin, $viewTranslation);
            $this->authManager->addChild($admin, $indexTranslation);
            $this->authManager->addChild($admin, $updateTranslation);
            $this->authManager->addChild($admin, $deleteTranslation);

            // $this->authManager->addChild($admin, $createSetting);
            $this->authManager->addChild($admin, $viewSetting);
            $this->authManager->addChild($admin, $indexSetting);
            $this->authManager->addChild($admin, $updateSetting);
            $this->authManager->addChild($admin, $deleteSetting);

            $this->authManager->addChild($developer, $admin);
            $this->authManager->addChild($developer, $createSetting);

            echo 'All child permissions has been added.' . PHP_EOL;

            echo 'RBAC generate Complete.' . PHP_EOL;
        }

        /**
         * Add rule which checks user foreign rights
         * @return \yii\rbac\Permission
         */
        protected function createForeignAccess()
        {
            $foreignAccessRule = new ForeignAccessRule();
            $this->authManager->add($foreignAccessRule);
            echo 'foreignAccess rule has been created.' . PHP_EOL;

            $foreignAccess = $this->authManager->createPermission('foreignAccess');
            echo 'Foreign permission has been created.' . PHP_EOL;

            $foreignAccess->description = 'Доступ к чужому контенту';
            $foreignAccess->ruleName    = $foreignAccessRule->name;
            echo 'Foreign permission attributes has been saved.' . PHP_EOL;

            $this->authManager->add($foreignAccess);
            echo 'Foreign permission has been added.' . PHP_EOL;

            return $foreignAccess;
        }

        /**
         * Add rule which checks user owner rights
         * @return \yii\rbac\Permission
         */
        protected function createOwnerAccess()
        {
            $ownerAccessRule = new OwnerAccessRule();
            $this->authManager->add($ownerAccessRule);
            echo 'ownerAccess rule has been created.' . PHP_EOL;

            $ownerAccess = $this->authManager->createPermission('ownerAccess');
            echo 'Owner permission has been created.' . PHP_EOL;

            $ownerAccess->description = 'Доступ к своему контенту';
            $ownerAccess->ruleName    = $ownerAccessRule->name;
            echo 'Owner permission attributes has been saved.' . PHP_EOL;

            $this->authManager->add($ownerAccess);
            echo 'Owner permission has been added.' . PHP_EOL;

            return $ownerAccess;
        }
    }
