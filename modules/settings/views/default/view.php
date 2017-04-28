<?php

    use papalapa\yiistart\models\User;
    use papalapa\yiistart\widgets\ControlButtonsPanel;
    use yii\helpers\Html;
    use yii\widgets\DetailView;

    /* @var $this yii\web\View */
    /* @var $model common\models\Settings */

    $this->title                   = $model->key;
    $this->params['breadcrumbs'][] = ['label' => 'Настройки', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="settings-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
        echo ControlButtonsPanel::widget([
            'items' => [
                'updateSetting' => [
                    'title' => 'Изменить',
                    'url'   => ['update', 'id' => $model->id],
                    'class' => 'btn btn-success',
                ],
                'deleteSetting' => [
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
            // 'id',
            'key',
            'value:ntext',
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
