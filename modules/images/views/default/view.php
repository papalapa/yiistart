<?php

    use papalapa\yiistart\models\BaseUser;
    use papalapa\yiistart\widgets\ControlButtonsPanel;
    use papalapa\yiistart\widgets\MultilingualDetailView;
    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model \papalapa\yiistart\modules\photo\models\Photo */

    $this->title                   = sprintf('№%s', $model->id, $model->order);
    $this->params['breadcrumbs'][] = ['label' => 'Фотографии', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="photo-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?
        echo ControlButtonsPanel::widget([
            'items' => [
                'updatePhoto' => [
                    'title' => 'Изменить',
                    'url'   => ['update', 'id' => $model->id],
                    'ico'   => 'fa fa-pencil',
                    'class' => 'btn btn-success',
                ],
                'deletePhoto' => [
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

    <?= MultilingualDetailView::widget([
        'model'      => $model,
        'attributes' => [
            'id',
            'title',
            'text:text',
            [
                'attribute' => 'image',
                'format'    => 'html',
                'value'     => function ($model) /* @var \papalapa\yiistart\modules\photo\models\Photo $model */ {
                    return $model->image ? Html::img($model->image, ['width' => 200]) : null;
                },
            ],
            'size',
            'width',
            'height',
            'order',
            [
                'attribute' => 'is_active',
                'value'     => Html::tag('i', null,
                    ['class' => $model->is_active ? 'fa fa-check text-success' : 'fa fa-times-circle text-danger']),
                'format'    => 'html',
            ],
            [
                'attribute' => 'created_by',
                'value'     => $model->created_by ? BaseUser::findOne(['id' => $model->created_by])->email : null,
                'format'    => 'email',
            ],
            [
                'attribute' => 'updated_by',
                'value'     => $model->updated_by ? BaseUser::findOne(['id' => $model->updated_by])->email : null,
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
