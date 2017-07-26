<?php

    use papalapa\yiistart\widgets\ControlButtonsPanel;
    use yii\helpers\Html;
    use yii\widgets\DetailView;

    /* @var $this yii\web\View */
    /* @var $model papalapa\yiistart\modules\i18n\models\SourceMessageCategories */

    $this->title                   = $model->category;
    $this->params['breadcrumbs'][] = ['label' => 'Описание категорий переводов', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="source-message-categories-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= ControlButtonsPanel::widget([
        'items' => [
            'updateSourceMessageCategory' => [
                'title' => 'Изменить',
                'url'   => ['update', 'id' => $model->primaryKey],
                'ico'   => 'fa fa-pencil',
                'class' => 'btn btn-success',
            ],
            'deleteSourceMessageCategory' => [
                'title' => 'Удалить',
                'url'   => ['delete', 'id' => $model->primaryKey],
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
            'category',
            'translate',
        ],
    ]) ?>

</div>
