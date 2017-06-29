<?php

    /* @var $this yii\web\View */

    use papalapa\yiistart\modules\users\models\BaseUser;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;

    $this->title = 'Admin panel';

    $siteUrlManager          = clone Yii::$app->urlManager;
    $siteUrlManager->baseUrl = '/';

?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Привет!</h1>

        <p class="lead">По нашим данным вы -
            <b><?= Yii::$app->user->isGuest ? 'гость' : ArrayHelper::getValue(BaseUser::roleDescription(), Yii::$app->user->identity->role) ?></b>
                        (статус <i><?= ArrayHelper::getValue(BaseUser::statuses(), Yii::$app->user->identity->status) ?></i>).
        </p>

        <p><a class="btn btn-lg btn-success" href="<?= $siteUrlManager->createUrl(['/']) ?>">Вернуться на сайт</a></p>
    </div>

    <div class="body-content">
        <h2>Ваши права:</h2>
        <table class="table table-stripped table-bordered">
            <thead>
            <tr>
                <th>Тип</th>
                <th>Доступ</th>
            </tr>
            </thead>
            <tbody>
            <?
                $roleName          = ArrayHelper::getValue(BaseUser::roles(), Yii::$app->user->identity->role);
                $permissions       = Yii::$app->authManager->getPermissions();
                $permissions       = ArrayHelper::map($permissions, 'name', 'description');
                $accessPermissions = Yii::$app->authManager->getPermissionsByRole($roleName);
                $accessPermissions = ArrayHelper::map($accessPermissions, 'name', 'description');
                $deniedPermissions = array_diff($permissions, $accessPermissions);
                if ($permissions) {
                    foreach ($permissions as $permission => $name) {
                        ?>
                        <tr>
                            <td><?= $name ?></td>
                            <td>
                                <?
                                    echo array_key_exists($permission, $deniedPermissions)
                                        ? Html::tag('i', null, ['class' => 'fa fa-times-circle text-danger'])
                                        : Html::tag('i', null, ['class' => 'fa fa-check text-success']);
                                ?>
                            </td>
                        </tr>
                        <?
                    }
                }
            ?>
            </tbody>
        </table>
    </div>
</div>
