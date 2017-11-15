<?php

    use papalapa\yiistart\modules\users\models\BaseUser;
    use papalapa\yiistart\widgets\ControlButtonsPanel;
    use papalapa\yiistart\widgets\MultilingualDetailView;
    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model papalapa\yiistart\modules\image\models\Image */

    $this->title                   = $model->name ? : '#'.$model->order;
    $this->params['breadcrumbs'][] = ['label' => 'Изображения', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="image-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= ControlButtonsPanel::widget([
        'items' => [
            'updateImage' => [
                'title' => 'Изменить',
                'url'   => ['update', 'id' => $model->id],
                'ico'   => 'fa fa-pencil',
                'class' => 'btn btn-success',
            ],
            'deleteImage' => [
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

    <?= MultilingualDetailView::widget([
        'model'      => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'album_id',
                'value'     => $model->album ? $model->album->name : null,
            ],
            [
                'attribute' => 'name',
                'visible'   => $model->album->has_name,
            ],
            [
                'attribute' => 'alt',
                'visible'   => $model->album->has_alt,
            ],
            [
                'attribute' => 'title',
                'visible'   => $model->album->has_title,
            ],
            [
                'attribute' => 'text',
                'visible'   => $model->album->has_text,
            ],
            [
                'attribute' => 'caption',
                'visible'   => $model->album->has_caption,
            ],
            [
                'attribute' => 'src',
                'visible'   => $model->album->has_src,
            ],
            [
                'attribute' => 'cssclass',
                'visible'   => $model->album->has_cssclass,
            ],
            [
                'attribute' => 'twin',
                'visible'   => $model->album->has_twin,
            ],
            [
                'attribute' => 'twin_cssclass',
                'visible'   => $model->album->has_twin_cssclass,
            ],
            [
                'attribute' => 'link',
                'visible'   => $model->album->has_link,
            ],
            [
                'attribute' => 'link_cssclass',
                'visible'   => $model->album->has_link_cssclass,
            ],
            [
                'attribute' => 'size',
                'visible'   => $model->album->has_src,
            ],
            [
                'attribute' => 'width',
                'visible'   => $model->album->has_src,
            ],
            [
                'attribute' => 'height',
                'visible'   => $model->album->has_src,
            ],
            'order',
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
