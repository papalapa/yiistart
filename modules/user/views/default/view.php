<?php

    use backend\widgets\Permissions;
    use common\models\User;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\widgets\DetailView;

    /* @var $this yii\web\View */
    /* @var $model common\models\User */

    $this->title                   = $model->email;
    $this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?
        echo Permissions::widget([
            'items' => [
                'updateUser' => [
                    'title' => 'Изменить',
                    'url'   => ['update', 'id' => $model->id],
                    'class' => 'btn btn-success',
                ],
                'deleteUser' => [
                    'title' => 'Удалить',
                    'url'   => ['delete', 'id' => $model->id],
                    'class' => 'btn btn-danger',
                    'data'  => [
                        'confirm' => 'Вы уверены, что хотите удалить?',
                        'method'  => 'post',
                    ],
                ],
            ],
        ]);
    ?>

    <?= DetailView::widget([
        'model'      => $model,
        'attributes' => [
            'id',
            'email:email',
            //'auth_key',
            //'password_hash',
            //'token',
            [
                'attribute' => 'status',
                'value'     => function ($model) {
                    return ArrayHelper::getValue(User::statusDescription(), $model->status);
                },
            ],
            [
                'attribute' => 'role',
                'value'     => function ($model) {
                    return ArrayHelper::getValue(User::roleDescription(), $model->role);
                },
            ],
            [
                'attribute' => 'created_at',
                'value'     => Yii::$app->formatter->asDate($model->created_at, 'd MMMM YYYY, HH:mm'),
                'format'    => 'html',
            ],
            [
                'attribute' => 'updated_at',
                'value'     => Yii::$app->formatter->asDate($model->updated_at, 'd MMMM YYYY, HH:mm'),
                'format'    => 'html',
            ],
            [
                'attribute' => 'activity_at',
                'value'     => function ($model) {
                    return Yii::$app->formatter->asDate($model->activity_at, 'd MMMM YYYY, HH:mm');
                },
                'format' => 'html',
            ],
            [
                'attribute' => 'last_ip',
                'value'     => function ($model) {
                    return long2ip($model->last_ip ? $model->last_ip : 0);
                },
            ],
        ],
    ]) ?>

</div>
