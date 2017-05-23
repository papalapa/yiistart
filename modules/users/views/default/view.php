<?php

    use papalapa\yiistart\modules\users\models\BaseUser;
    use papalapa\yiistart\widgets\ControlButtonsPanel;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\widgets\DetailView;

    /* @var $this yii\web\View */
    /* @var $model \papalapa\yiistart\modules\users\models\User */

    $this->title                   = $model->email;
    $this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?
        echo ControlButtonsPanel::widget([
            'items' => [
                'updateUser' => [
                    'title' => 'Изменить',
                    'url'   => ['update', 'id' => $model->id],
                    'ico'   => 'fa fa-pencil',
                    'class' => 'btn btn-success',
                ],
                'deleteUser' => [
                    'title' => 'Удалить',
                    'url'   => ['delete', 'id' => $model->id],
                    'class' => 'btn btn-danger',
                    'ico'   => 'fa fa-trash',
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
                    return ArrayHelper::getValue(BaseUser::statusDescription(), $model->status);
                },
            ],
            [
                'attribute' => 'role',
                'value'     => function ($model) {
                    return ArrayHelper::getValue(BaseUser::roleDescription(), $model->role);
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
                'format'    => 'html',
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
