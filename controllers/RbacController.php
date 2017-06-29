<?php

    namespace papalapa\yiistart\controllers;

    use papalapa\yiistart\modules\users\models\BaseUser;
    use papalapa\yiistart\rbac\ForeignAccessRule;
    use papalapa\yiistart\rbac\OwnerAccessRule;
    use papalapa\yiistart\rbac\UserRoleRule;
    use yii\console\Controller;
    use yii\rbac\ManagerInterface;

    /**
     * Class RbacController
     * @package papalapa\yiistart\controllers
     */
    class RbacController extends Controller
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
            echo 'Old rules has been removed.'.PHP_EOL;

            $this->createRoles();
            $this->createOwnerAccessRule();
            $this->createForeignAccessRule();
            $this->loadPermissions();
        }

        /**
         * Add rule which checks user foreign rights
         */
        protected function createForeignAccessRule()
        {
            $foreignAccessRule = new ForeignAccessRule();
            $this->authManager->add($foreignAccessRule);
            echo 'foreignAccess rule has been created.'.PHP_EOL;

            $foreignAccess = $this->authManager->createPermission('foreignAccess');
            echo 'Foreign permission has been created.'.PHP_EOL;

            $foreignAccess->description = 'Доступ к чужому контенту';
            $foreignAccess->ruleName    = $foreignAccessRule->name;
            echo 'Foreign permission attributes has been saved.'.PHP_EOL;

            $this->authManager->add($foreignAccess);
            echo 'Foreign permission has been added.'.PHP_EOL;

            $this->foreignPermission = $foreignAccess;
        }

        /**
         * Add rule which checks user owner rights
         */
        protected function createOwnerAccessRule()
        {
            $ownerAccessRule = new OwnerAccessRule();
            $this->authManager->add($ownerAccessRule);
            echo 'ownerAccess rule has been created.'.PHP_EOL;

            $ownerAccess = $this->authManager->createPermission('ownerAccess');
            echo 'Owner permission has been created.'.PHP_EOL;

            $ownerAccess->description = 'Доступ к своему контенту';
            $ownerAccess->ruleName    = $ownerAccessRule->name;
            echo 'Owner permission attributes has been saved.'.PHP_EOL;

            $this->authManager->add($ownerAccess);
            echo 'Owner permission has been added.'.PHP_EOL;

            $this->ownerPermission = $ownerAccess;
        }

        /**
         * Generating user roles
         */
        protected function createRoles()
        {
            $userRoleRule = new UserRoleRule();
            $this->authManager->add($userRoleRule);

            $this->roles[BaseUser::ROLE_GUEST]     = $this->authManager->createRole(BaseUser::roles()[BaseUser::ROLE_GUEST]);
            $this->roles[BaseUser::ROLE_USER]      = $this->authManager->createRole(BaseUser::roles()[BaseUser::ROLE_USER]);
            $this->roles[BaseUser::ROLE_AUTHOR]    = $this->authManager->createRole(BaseUser::roles()[BaseUser::ROLE_AUTHOR]);
            $this->roles[BaseUser::ROLE_MANAGER]   = $this->authManager->createRole(BaseUser::roles()[BaseUser::ROLE_MANAGER]);
            $this->roles[BaseUser::ROLE_ADMIN]     = $this->authManager->createRole(BaseUser::roles()[BaseUser::ROLE_ADMIN]);
            $this->roles[BaseUser::ROLE_DEVELOPER] = $this->authManager->createRole(BaseUser::roles()[BaseUser::ROLE_DEVELOPER]);
            echo 'User roles has been created.'.PHP_EOL;

            $this->roles[BaseUser::ROLE_GUEST]->ruleName     = $userRoleRule->name;
            $this->roles[BaseUser::ROLE_USER]->ruleName      = $userRoleRule->name;
            $this->roles[BaseUser::ROLE_AUTHOR]->ruleName    = $userRoleRule->name;
            $this->roles[BaseUser::ROLE_MANAGER]->ruleName   = $userRoleRule->name;
            $this->roles[BaseUser::ROLE_ADMIN]->ruleName     = $userRoleRule->name;
            $this->roles[BaseUser::ROLE_DEVELOPER]->ruleName = $userRoleRule->name;

            $this->authManager->add($this->roles[BaseUser::ROLE_GUEST]);
            $this->authManager->add($this->roles[BaseUser::ROLE_USER]);
            $this->authManager->add($this->roles[BaseUser::ROLE_AUTHOR]);
            $this->authManager->add($this->roles[BaseUser::ROLE_MANAGER]);
            $this->authManager->add($this->roles[BaseUser::ROLE_ADMIN]);
            $this->authManager->add($this->roles[BaseUser::ROLE_DEVELOPER]);
            echo 'User roles has been added.'.PHP_EOL;
        }

        /**
         * Making rules to control content
         * There are five default permissions:
         * - create content
         * - view content (with attribute is_active = false)
         * - listing content (include models with attribute is_active = false)
         * - update content
         * - delete content
         */
        protected function loadPermissions()
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

            /** Управление меню */
            $createMenu              = $this->authManager->createPermission('createMenu');
            $createMenu->description = 'Создание пунктов меню';
            $viewMenu                = $this->authManager->createPermission('viewMenu');
            $viewMenu->description   = 'Просмотр пунктов меню';
            $indexMenu               = $this->authManager->createPermission('indexMenu');
            $indexMenu->description  = 'Листинг пунктов меню';
            $updateMenu              = $this->authManager->createPermission('updateMenu');
            $updateMenu->description = 'Изменение пунктов меню';
            $deleteMenu              = $this->authManager->createPermission('deleteMenu');
            $deleteMenu->description = 'Удаление пунктов меню';

            /** Управление категориями элементов страниц */
            $createElementCategory              = $this->authManager->createPermission('createElementCategory');
            $createElementCategory->description = 'Создание категорий элементов страниц';
            $viewElementCategory                = $this->authManager->createPermission('viewElementCategory');
            $viewElementCategory->description   = 'Просмотр категорий элементов страниц';
            $indexElementCategory               = $this->authManager->createPermission('indexElementCategory');
            $indexElementCategory->description  = 'Листинг категорий элементов страниц';
            $updateElementCategory              = $this->authManager->createPermission('updateElementCategory');
            $updateElementCategory->description = 'Изменение категорий элементов страниц';
            $deleteElementCategory              = $this->authManager->createPermission('deleteElementCategory');
            $deleteElementCategory->description = 'Удаление категорий элементов страниц';

            /** Управление элементами страниц */
            $createElement              = $this->authManager->createPermission('createElement');
            $createElement->description = 'Создание элементов страниц';
            $viewElement                = $this->authManager->createPermission('viewElement');
            $viewElement->description   = 'Просмотр элементов страниц';
            $indexElement               = $this->authManager->createPermission('indexElement');
            $indexElement->description  = 'Листинг элементов страниц';
            $updateElement              = $this->authManager->createPermission('updateElement');
            $updateElement->description = 'Изменение элементов страниц';
            $deleteElement              = $this->authManager->createPermission('deleteElement');
            $deleteElement->description = 'Удаление элементов страниц';

            /** Управление рассылками */
            $createDispatch              = $this->authManager->createPermission('createDispatch');
            $createDispatch->description = 'Создание подписок';
            $viewDispatch                = $this->authManager->createPermission('viewDispatch');
            $viewDispatch->description   = 'Просмотр подписок';
            $indexDispatch               = $this->authManager->createPermission('indexDispatch');
            $indexDispatch->description  = 'Листинг подписок';
            $updateDispatch              = $this->authManager->createPermission('updateDispatch');
            $updateDispatch->description = 'Изменение подписок';
            $deleteDispatch              = $this->authManager->createPermission('deleteDispatch');
            $deleteDispatch->description = 'Удаление подписок';

            /** Управление подписчиками */
            $createSubscriber              = $this->authManager->createPermission('createSubscriber');
            $createSubscriber->description = 'Создание подписчика';
            $viewSubscriber                = $this->authManager->createPermission('viewSubscriber');
            $viewSubscriber->description   = 'Просмотр подписчика';
            $indexSubscriber               = $this->authManager->createPermission('indexSubscriber');
            $indexSubscriber->description  = 'Листинг подписчика';
            $updateSubscriber              = $this->authManager->createPermission('updateSubscriber');
            $updateSubscriber->description = 'Изменение подписчика';
            $deleteSubscriber              = $this->authManager->createPermission('deleteSubscriber');
            $deleteSubscriber->description = 'Удаление подписчика';

            /** Управление категориями изображений */
            $createImageCategory              = $this->authManager->createPermission('createImageCategory');
            $createImageCategory->description = 'Создание категорий изображений';
            $viewImageCategory                = $this->authManager->createPermission('viewImageCategory');
            $viewImageCategory->description   = 'Просмотр категорий изображений';
            $indexImageCategory               = $this->authManager->createPermission('indexImageCategory');
            $indexImageCategory->description  = 'Листинг категорий изображений';
            $updateImageCategory              = $this->authManager->createPermission('updateImageCategory');
            $updateImageCategory->description = 'Изменение категорий изображений';
            $deleteImageCategory              = $this->authManager->createPermission('deleteImageCategory');
            $deleteImageCategory->description = 'Удаление категорий изображений';

            /** Управление изображениями */
            $createImage              = $this->authManager->createPermission('createImage');
            $createImage->description = 'Создание изображений';
            $viewImage                = $this->authManager->createPermission('viewImage');
            $viewImage->description   = 'Просмотр изображений';
            $indexImage               = $this->authManager->createPermission('indexImage');
            $indexImage->description  = 'Листинг изображений';
            $updateImage              = $this->authManager->createPermission('updateImage');
            $updateImage->description = 'Изменение изображений';
            $deleteImage              = $this->authManager->createPermission('deleteImage');
            $deleteImage->description = 'Удаление изображений';

            /** Управление партнерами компании */
            $createPartner              = $this->authManager->createPermission('createPartner');
            $createPartner->description = 'Создание партнеров';
            $viewPartner                = $this->authManager->createPermission('viewPartner');
            $viewPartner->description   = 'Просмотр партнеров';
            $indexPartner               = $this->authManager->createPermission('indexPartner');
            $indexPartner->description  = 'Листинг партнеров';
            $updatePartner              = $this->authManager->createPermission('updatePartner');
            $updatePartner->description = 'Изменение партнеров';
            $deletePartner              = $this->authManager->createPermission('deletePartner');
            $deletePartner->description = 'Удаление партнеров';

            echo 'New permissions has been created.'.PHP_EOL;

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

            $this->authManager->add($createMenu);
            $this->authManager->add($viewMenu);
            $this->authManager->add($indexMenu);
            $this->authManager->add($updateMenu);
            $this->authManager->add($deleteMenu);

            $this->authManager->add($createElementCategory);
            $this->authManager->add($viewElementCategory);
            $this->authManager->add($indexElementCategory);
            $this->authManager->add($updateElementCategory);
            $this->authManager->add($deleteElementCategory);

            $this->authManager->add($createElement);
            $this->authManager->add($viewElement);
            $this->authManager->add($indexElement);
            $this->authManager->add($updateElement);
            $this->authManager->add($deleteElement);

            $this->authManager->add($createDispatch);
            $this->authManager->add($viewDispatch);
            $this->authManager->add($indexDispatch);
            $this->authManager->add($updateDispatch);
            $this->authManager->add($deleteDispatch);

            $this->authManager->add($createSubscriber);
            $this->authManager->add($viewSubscriber);
            $this->authManager->add($indexSubscriber);
            $this->authManager->add($updateSubscriber);
            $this->authManager->add($deleteSubscriber);

            $this->authManager->add($createImageCategory);
            $this->authManager->add($viewImageCategory);
            $this->authManager->add($indexImageCategory);
            $this->authManager->add($updateImageCategory);
            $this->authManager->add($deleteImageCategory);

            $this->authManager->add($createImage);
            $this->authManager->add($viewImage);
            $this->authManager->add($indexImage);
            $this->authManager->add($updateImage);
            $this->authManager->add($deleteImage);

            $this->authManager->add($createPartner);
            $this->authManager->add($viewPartner);
            $this->authManager->add($indexPartner);
            $this->authManager->add($updatePartner);
            $this->authManager->add($deletePartner);

            echo 'New permissions has been added.'.PHP_EOL;

            // ------------------------------------------------------------------------------

            /** Добавление правил */
            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $this->ownerPermission);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $this->foreignPermission);

            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $createUser);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $viewUser);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $indexUser);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $updateUser);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $deleteUser);

            // $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $createTranslation); // translation creates automatic
            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $viewTranslation);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $indexTranslation);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $updateTranslation);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $deleteTranslation);

            // $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $createSetting); // only for developer
            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $viewSetting);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $indexSetting);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $updateSetting);
            // $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $deleteSetting); // only for developer

            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $createPage);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $viewPage);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $indexPage);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $updatePage);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $deletePage);

            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $createMenu);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $viewMenu);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $indexMenu);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $updateMenu);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $deleteMenu);

            // $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $createElement); // only for developer
            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $viewElement);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $indexElement);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $updateElement);
            // $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $deleteElement); // only for developer

            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $createDispatch);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $viewDispatch);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $indexDispatch);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $updateDispatch);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $deleteDispatch);

            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $createSubscriber);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $viewSubscriber);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $indexSubscriber);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $updateSubscriber);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $deleteSubscriber);

            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $createImage);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $viewImage);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $indexImage);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $updateImage);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $deleteImage);

            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $createPartner);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $viewPartner);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $indexPartner);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $updatePartner);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_ADMIN], $deletePartner);

            /** Developer */

            $this->authManager->addChild($this->roles[BaseUser::ROLE_DEVELOPER], $createSetting);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_DEVELOPER], $deleteSetting);

            $this->authManager->addChild($this->roles[BaseUser::ROLE_DEVELOPER], $createElement);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_DEVELOPER], $deleteElement);

            $this->authManager->addChild($this->roles[BaseUser::ROLE_DEVELOPER], $createElementCategory);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_DEVELOPER], $viewElementCategory);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_DEVELOPER], $indexElementCategory);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_DEVELOPER], $updateElementCategory);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_DEVELOPER], $deleteElementCategory);

            $this->authManager->addChild($this->roles[BaseUser::ROLE_DEVELOPER], $createImageCategory);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_DEVELOPER], $viewImageCategory);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_DEVELOPER], $indexImageCategory);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_DEVELOPER], $updateImageCategory);
            $this->authManager->addChild($this->roles[BaseUser::ROLE_DEVELOPER], $deleteImageCategory);

            echo 'All child permissions has been added.'.PHP_EOL;

            echo 'RBAC generate complete.'.PHP_EOL;
        }
    }
