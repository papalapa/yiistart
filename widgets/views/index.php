<?php

    /* @var $this yii\web\View */

    use papalapa\yiistart\modules\users\models\BaseUser;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;

    $this->title = 'Панель администрирования';

    $siteUrlManager          = clone Yii::$app->urlManager;
    $siteUrlManager->baseUrl = '/';

    $roleName          = ArrayHelper::getValue(BaseUser::roles(), Yii::$app->user->identity->role);
    $permissions       = Yii::$app->authManager->getPermissions();
    $permissions       = ArrayHelper::map($permissions, 'name', 'description');
    $accessPermissions = Yii::$app->authManager->getPermissionsByRole($roleName);
    $accessPermissions = ArrayHelper::map($accessPermissions, 'name', 'description');
    $deniedPermissions = array_diff($permissions, $accessPermissions);

?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Привет!</h1>

        <p class="lead">
            По нашим данным вы -
            <b><?= ArrayHelper::getValue(BaseUser::roleDescription(), Yii::$app->user->identity->role) ?></b>
            (статус <i>«<?= ArrayHelper::getValue(BaseUser::statusDescription(), Yii::$app->user->identity->status) ?>»</i>).
        </p>

        <p><a class="btn btn-lg btn-success" href="<?= $siteUrlManager->createUrl(['/']) ?>">Вернуться на сайт</a></p>
    </div>

    <? if ($permissions) : ?>
        <div class="body-content">
            <h2>Ваши права:</h2>
            <table class="table table-stripped table-bordered table-responsive">
                <thead>
                <tr>
                    <th>Тип</th>
                    <th>Доступ</th>
                </tr>
                </thead>
                <tbody>
                <? foreach ($permissions as $permission => $name) : ?>
                    <tr>
                        <td><?= $name ?></td>
                        <td>
                            <?= array_key_exists($permission, $deniedPermissions)
                                ? Html::tag('i', null, ['class' => 'fa fa-times-circle text-danger'])
                                : Html::tag('i', null, ['class' => 'fa fa-check text-success']);
                            ?>
                        </td>
                    </tr>
                <? endforeach; ?>
                </tbody>
            </table>
        </div>
    <? endif; ?>
</div>
