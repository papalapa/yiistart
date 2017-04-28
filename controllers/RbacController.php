<?php

    namespace papalapa\yiistart\controllers;

    use papalapa\yiistart\rbac\ForeignAccessRule;
    use papalapa\yiistart\rbac\OwnerAccessRule;
    use papalapa\yiistart\rbac\UserRoleRule;
    use papalapa\yiistart\rbac\Controller;

    /**
     * Class RbacController
     * @package papalapa\yiistart\controllers
     */
    class RbacController extends Controller
    {
        public function actionInit()
        {
            $authManager = \Yii::$app->authManager;
            $authManager->removeAll();

            echo 'All rules has been removed.' . PHP_EOL;

            // ------------------------------------------------------------------------------

            /**
             * Регистрируем определенный набор разрешений:
             * 1. создание
             * 2. Изменение
             * 3. удаление
             * 4. индексирование неактивных элементов (атрибут is_active)
             * 5. просмотр неактивных элементов (атрибут is_active)
             */

            // управление пользователями
            $createUser              = $authManager->createPermission('createUser');
            $createUser->description = 'Создание пользователей';
            $viewUser                = $authManager->createPermission('viewUser');
            $viewUser->description   = 'Просмотр пользователей';
            $indexUser               = $authManager->createPermission('indexUser');
            $indexUser->description  = 'Листинг пользователей';
            $updateUser              = $authManager->createPermission('updateUser');
            $updateUser->description = 'Изменение пользователей';
            $deleteUser              = $authManager->createPermission('deleteUser');
            $deleteUser->description = 'Удаление пользователей';

            echo 'New permissions has been created.' . PHP_EOL;

            // ------------------------------------------------------------------------------

            // добавляем все вышеуказанные правила в менеджер
            $authManager->add($createUser);
            $authManager->add($viewUser);
            $authManager->add($indexUser);
            $authManager->add($updateUser);
            $authManager->add($deleteUser);

            echo 'New permissions has been added.' . PHP_EOL;

            // ------------------------------------------------------------------------------

            // добавляем правило, основанное на userRole
            $userRoleRule = new UserRoleRule();
            $authManager->add($userRoleRule);

            // создаем роли пользователей
            $guest     = $authManager->createRole('guest');
            $user      = $authManager->createRole('user');
            $author    = $authManager->createRole('author');
            $manager   = $authManager->createRole('manager');
            $admin     = $authManager->createRole('admin');
            $developer = $authManager->createRole('developer');

            echo 'User roles has been created.' . PHP_EOL;

            // добавляем это правило к ролям
            $guest->ruleName     = $userRoleRule->name;
            $user->ruleName      = $userRoleRule->name;
            $author->ruleName    = $userRoleRule->name;
            $manager->ruleName   = $userRoleRule->name;
            $admin->ruleName     = $userRoleRule->name;
            $developer->ruleName = $userRoleRule->name;

            // добавляем правила
            $authManager->add($guest);
            $authManager->add($user);
            $authManager->add($author);
            $authManager->add($manager);
            $authManager->add($admin);
            $authManager->add($developer);

            echo 'User roles has been added.' . PHP_EOL;

            // ------------------------------------------------------------------------------

            // добавляем правило, проверяющее принадлежность контента пользователю
            $ownerAccessRule = new OwnerAccessRule();
            $authManager->add($ownerAccessRule);

            $ownerAccess              = $authManager->createPermission('ownerAccess');
            $ownerAccess->description = 'Доступ к своему контенту';
            $ownerAccess->ruleName    = $ownerAccessRule->name;

            echo 'Owner permission has been created.' . PHP_EOL;

            // добавляем правила
            $authManager->add($ownerAccess);

            echo 'Owner permission has been added.' . PHP_EOL;

            // ------------------------------------------------------------------------------

            // добавляем правило, проверяющее возможность изменения чужого контента пользователем
            $foreignAccessRule = new ForeignAccessRule();
            $authManager->add($foreignAccessRule);

            $foreignAccess              = $authManager->createPermission('foreignAccess');
            $foreignAccess->description = 'Доступ к чужому контенту';
            $foreignAccess->ruleName    = $foreignAccessRule->name;

            echo 'Foreign permission has been created.' . PHP_EOL;

            // добавляем правила
            $authManager->add($foreignAccess);

            echo 'Foreign permission has been added.' . PHP_EOL;

            // ------------------------------------------------------------------------------

            // добавляем разрешения

            // админ
            $authManager->addChild($admin, $ownerAccess);
            $authManager->addChild($admin, $foreignAccess);

            $authManager->addChild($admin, $createUser);
            $authManager->addChild($admin, $viewUser);
            $authManager->addChild($admin, $indexUser);
            $authManager->addChild($admin, $updateUser);
            authManager->addChild($admin, $deleteUser);

            echo 'All child permissions has been added.' . PHP_EOL;
            
            echo 'RBAC Complete.' . PHP_EOL;
        }
    }
