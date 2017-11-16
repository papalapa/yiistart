<?php

    use papalapa\yiistart\modules\users\models\BaseUser;
    use papalapa\yiistart\widgets\ControlButtonsPanel;
    use yii\helpers\Html;
    use yii\widgets\DetailView;

    /* @var $this yii\web\View */
    /* @var $model papalapa\yiistart\modules\image\models\Album */

    $this->title                   = $model->name;
    $this->params['breadcrumbs'][] = ['label' => 'Альбомы', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="album-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= ControlButtonsPanel::widget([
        'items' => [
            'updateAlbum' => [
                'title' => 'Изменить',
                'url'   => ['update', 'id' => $model->id],
                'ico'   => 'fa fa-pencil',
                'class' => 'btn btn-success',
            ],
            'deleteAlbum' => [
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
    ]); ?>

    <?= DetailView::widget([
        'model'      => $model,
        'attributes' => [
            'id',
            'alias',
            'name',
            'scale',
            'template',
            [
                'attribute' => 'has_name',
                'value'     => Html::tag('i', null,
                    ['class' => $model->has_name ? 'fa fa-check text-success' : 'fa fa-times-circle text-danger']),
                'format'    => 'html',
            ],
            [
                'attribute' => 'has_alt',
                'value'     => Html::tag('i', null,
                    ['class' => $model->has_alt ? 'fa fa-check text-success' : 'fa fa-times-circle text-danger']),
                'format'    => 'html',
            ],
            [
                'attribute' => 'has_title',
                'value'     => Html::tag('i', null,
                    ['class' => $model->has_title ? 'fa fa-check text-success' : 'fa fa-times-circle text-danger']),
                'format'    => 'html',
            ],
            [
                'attribute' => 'has_text',
                'value'     => Html::tag('i', null,
                    ['class' => $model->has_text ? 'fa fa-check text-success' : 'fa fa-times-circle text-danger']),
                'format'    => 'html',
            ],
            [
                'attribute' => 'has_caption',
                'value'     => Html::tag('i', null,
                    ['class' => $model->has_caption ? 'fa fa-check text-success' : 'fa fa-times-circle text-danger']),
                'format'    => 'html',
            ],
            [
                'attribute' => 'has_src',
                'value'     => Html::tag('i', null,
                    ['class' => $model->has_src ? 'fa fa-check text-success' : 'fa fa-times-circle text-danger']),
                'format'    => 'html',
            ],
            [
                'attribute' => 'has_cssclass',
                'value'     => Html::tag('i', null,
                    ['class' => $model->has_cssclass ? 'fa fa-check text-success' : 'fa fa-times-circle text-danger']),
                'format'    => 'html',
            ],
            [
                'attribute' => 'has_twin',
                'value'     => Html::tag('i', null,
                    ['class' => $model->has_twin ? 'fa fa-check text-success' : 'fa fa-times-circle text-danger']),
                'format'    => 'html',
            ],
            [
                'attribute' => 'has_twin_cssclass',
                'value'     => Html::tag('i', null,
                    ['class' => $model->has_twin_cssclass ? 'fa fa-check text-success' : 'fa fa-times-circle text-danger']),
                'format'    => 'html',
            ],
            [
                'attribute' => 'has_link',
                'value'     => Html::tag('i', null,
                    ['class' => $model->has_link ? 'fa fa-check text-success' : 'fa fa-times-circle text-danger']),
                'format'    => 'html',
            ],
            [
                'attribute' => 'has_link_cssclass',
                'value'     => Html::tag('i', null,
                    ['class' => $model->has_link_cssclass ? 'fa fa-check text-success' : 'fa fa-times-circle text-danger']),
                'format'    => 'html',
            ],
            'validator_controller',
            'validator_extensions',
            'validator_min_size',
            'validator_max_size',
            'description:html',
            [
                'attribute' => 'is_multilingual_images',
                'value'     => Html::tag('i', null,
                    ['class' => $model->is_multilingual_images ? 'fa fa-check text-success' : 'fa fa-times-circle text-danger']),
                'format'    => 'html',
            ],
            [
                'attribute' => 'is_visible',
                'value'     => Html::tag('i', null,
                    ['class' => $model->is_visible ? 'fa fa-check text-success' : 'fa fa-times-circle text-danger']),
                'format'    => 'html',
            ],
            [
                'attribute' => 'is_active',
                'value'     => Html::tag('i', null,
                    ['class' => $model->is_active ? 'fa fa-check text-success' : 'fa fa-times-circle text-danger']),
                'format'    => 'html',
            ],
            [
                'attribute' => 'created_by',
                'value'     => ($model->created_by && $user = BaseUser::findOne(['id' => $model->created_by])) ? $user->email : null,
                'format'    => 'email',
            ],
            [
                'attribute' => 'updated_by',
                'value'     => ($model->updated_by && $user = BaseUser::findOne(['id' => $model->updated_by])) ? $user->email : null,
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
