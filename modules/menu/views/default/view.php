<?php

    use papalapa\yiistart\models\User;
    use papalapa\yiistart\widgets\ControlButtonsPanel;
    use papalapa\yiistart\widgets\MultilingualDetailView;
    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model papalapa\yiistart\modules\menu\models\Menu */

    $this->title                   = $model->title;
    $this->params['breadcrumbs'][] = ['label' => 'Меню', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?
        echo ControlButtonsPanel::widget([
            'items' => [
                'updateMenu' => [
                    'title' => 'Изменить',
                    'url'   => ['update', 'id' => $model->id],
                    'ico'   => 'fa fa-pencil',
                    'class' => 'btn btn-success',
                ],
                'deleteMenu' => [
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

    <?php
        $siteUrlManager          = clone (Yii::$app->urlManager);
        $siteUrlManager->baseUrl = '/';
    ?>

    <?= MultilingualDetailView::widget([
        'model'      => $model,
        'attributes' => [
            [
                'attribute' => 'position',
                'value'     => $model->position,
            ],
            'sort',
            'title',
            [
                'attribute' => 'url',
                'format'    => 'raw',
                'value'     => Html::a($model->url, $siteUrlManager->createUrl([$model->url]), ['target' => '_blank']),
            ],
            [
                'attribute' => 'is_active',
                'value'     => Html::tag('i', null,
                    ['class' => $model->is_active ? 'fa fa-check text-success' : 'fa fa-times-circle text-danger']),
                'format'    => 'html',
            ],
            [
                'attribute' => 'created_by',
                'value'     => $model->created_by ? User::findOne(['id' => $model->created_by])->email : null,
                'format'    => 'email',
            ],
            [
                'attribute' => 'updated_by',
                'value'     => $model->updated_by ? User::findOne(['id' => $model->updated_by])->email : null,
                'format'    => 'email',
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
        ],
    ]) ?>

</div>
