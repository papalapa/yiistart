<?php

    namespace papalapa\yiistart\controllers;

    use papalapa\yiistart\models\User;
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
         * Permission to access to owner content
         * @var
         */
        public $ownerPermission;
        /**
         * Permission to access to foreign content
         * @var
         */
        public $foreignPermission;
        /**
         * @var ManagerInterface
         */
        protected $authManager;
        /**
         * @var array
         */
        protected $roles = [];

        /**
         * @inheritdoc
         */
        public function actionInit()
        {
            $this->authManager = \Yii::$app->authManager;
            $this->authManager->removeAll();
            echo 'Old rules has been removed.' . PHP_EOL;

            $this->createRoles();
            $this->createOwnerAccess();
            $this->createForeignAccess();
            $this->defaultPermissions();
        }

        /**
         * Add rule which checks user foreign rights
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

            $this->foreignPermission = $foreignAccess;
        }

        /**
         * Add rule which checks user owner rights
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

            $this->ownerPermission = $ownerAccess;
        }

        /**
         * Generating user roles
         */
        protected function createRoles()
        {
            $userRoleRule = new UserRoleRule();
            $this->authManager->add($userRoleRule);

            $this->roles[User::ROLE_GUEST]     = $this->authManager->createRole(User::roles()[User::ROLE_GUEST]);
            $this->roles[User::ROLE_USER]      = $this->authManager->createRole(User::roles()[User::ROLE_USER]);
            $this->roles[User::ROLE_AUTHOR]    = $this->authManager->createRole(User::roles()[User::ROLE_AUTHOR]);
            $this->roles[User::ROLE_MANAGER]   = $this->authManager->createRole(User::roles()[User::ROLE_MANAGER]);
            $this->roles[User::ROLE_ADMIN]     = $this->authManager->createRole(User::roles()[User::ROLE_ADMIN]);
            $this->roles[User::ROLE_DEVELOPER] = $this->authManager->createRole(User::roles()[User::ROLE_DEVELOPER]);

            echo 'User roles has been created.' . PHP_EOL;

            $this->roles[User::ROLE_GUEST]->ruleName     = $userRoleRule->name;
            $this->roles[User::ROLE_USER]->ruleName      = $userRoleRule->name;
            $this->roles[User::ROLE_AUTHOR]->ruleName    = $userRoleRule->name;
            $this->roles[User::ROLE_MANAGER]->ruleName   = $userRoleRule->name;
            $this->roles[User::ROLE_ADMIN]->ruleName     = $userRoleRule->name;
            $this->roles[User::ROLE_DEVELOPER]->ruleName = $userRoleRule->name;

            $this->authManager->add($this->roles[User::ROLE_GUEST]);
            $this->authManager->add($this->roles[User::ROLE_USER]);
            $this->authManager->add($this->roles[User::ROLE_AUTHOR]);
            $this->authManager->add($this->roles[User::ROLE_MANAGER]);
            $this->authManager->add($this->roles[User::ROLE_ADMIN]);
            $this->authManager->add($this->roles[User::ROLE_DEVELOPER]);

            echo 'User roles has been added.' . PHP_EOL;
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
        protected function defaultPermissions()
        {
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

            /** Управление страницами */
            $createPage              = $this->authManager->createPermission('createPage');
            $createPage->description = 'Создание страниц';
            $viewPage                = $this->authManager->createPermission('viewPage');
            $viewPage->description   = 'Просмотр страниц';
            $indexPage               = $this->authManager->createPermission('indexPage');
            $indexPage->description  = 'Листинг страниц';
            $updatePage              = $this->authManager->createPermission('updatePage');
            $updatePage->description = 'Изменение страниц';
            $deletePage              = $this->authManager->createPermission('deletePage');
            $deletePage->description = 'Удаление страниц';

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

            $this->authManager->add($createPage);
            $this->authManager->add($viewPage);
            $this->authManager->add($indexPage);
            $this->authManager->add($updatePage);
            $this->authManager->add($deletePage);

            echo 'New permissions has been added.' . PHP_EOL;

            // ------------------------------------------------------------------------------

            /** Добавление правил */
            $this->authManager->addChild($this->roles[User::ROLE_ADMIN], $this->ownerPermission);
            $this->authManager->addChild($this->roles[User::ROLE_ADMIN], $this->foreignPermission);

            $this->authManager->addChild($this->roles[User::ROLE_ADMIN], $createUser);
            $this->authManager->addChild($this->roles[User::ROLE_ADMIN], $viewUser);
            $this->authManager->addChild($this->roles[User::ROLE_ADMIN], $indexUser);
            $this->authManager->addChild($this->roles[User::ROLE_ADMIN], $updateUser);
            $this->authManager->addChild($this->roles[User::ROLE_ADMIN], $deleteUser);

            // $this->authManager->addChild($this->roles[User::ROLE_ADMIN], $createTranslation); // translations create automatic
            $this->authManager->addChild($this->roles[User::ROLE_ADMIN], $viewTranslation);
            $this->authManager->addChild($this->roles[User::ROLE_ADMIN], $indexTranslation);
            $this->authManager->addChild($this->roles[User::ROLE_ADMIN], $updateTranslation);
            $this->authManager->addChild($this->roles[User::ROLE_ADMIN], $deleteTranslation);

            // $this->authManager->addChild($this->roles[User::ROLE_ADMIN], $createSetting);
            $this->authManager->addChild($this->roles[User::ROLE_ADMIN], $viewSetting);
            $this->authManager->addChild($this->roles[User::ROLE_ADMIN], $indexSetting);
            $this->authManager->addChild($this->roles[User::ROLE_ADMIN], $updateSetting);
            $this->authManager->addChild($this->roles[User::ROLE_ADMIN], $deleteSetting);

            $this->authManager->addChild($this->roles[User::ROLE_ADMIN], $createPage);
            $this->authManager->addChild($this->roles[User::ROLE_ADMIN], $viewPage);
            $this->authManager->addChild($this->roles[User::ROLE_ADMIN], $indexPage);
            $this->authManager->addChild($this->roles[User::ROLE_ADMIN], $updatePage);
            $this->authManager->addChild($this->roles[User::ROLE_ADMIN], $deletePage);

            $this->authManager->addChild($this->roles[User::ROLE_DEVELOPER], $createSetting);

            echo 'All child permissions has been added.' . PHP_EOL;

            echo 'RBAC generate Complete.' . PHP_EOL;
        }
    }
