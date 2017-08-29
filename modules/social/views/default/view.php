<?php

    use papalapa\yiistart\widgets\ControlButtonsPanel;
    use yii\helpers\Html;
    use yii\widgets\DetailView;

    /* @var $this yii\web\View */
    /* @var $model papalapa\yiistart\modules\social\models\Social */

    $this->title                   = $model->name;
    $this->params['breadcrumbs'][] = ['label' => 'Социальные сети', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="social-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= ControlButtonsPanel::widget([
        'items' => [
            'updateSocial' => [
                'title' => 'Изменить',
                'url'   => ['update', 'id' => $model->id],
                'ico'   => 'fa fa-pencil',
                'class' => 'btn btn-success',
            ],
            'deleteSocial' => [
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
            'name',
            'position',
            'url:url',
            'image',
            'title',
            'alt',
            'order',
            'is_active',
            'created_by',
            'updated_by',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
