<?php

    use papalapa\yiistart\models\User;
    use papalapa\yiistart\widgets\ControlButtonsPanel;
    use yii\helpers\Html;
    use yii\widgets\DetailView;

    /* @var $this yii\web\View */
    /* @var $model papalapa\yiistart\modules\elements\models\ElementCategory */

    $this->title                   = $model->name;
    $this->params['breadcrumbs'][] = ['label' => 'Категории элементов', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="element-category-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?
        echo ControlButtonsPanel::widget([
            'items' => [
                'updateElementCategory' => [
                    'title' => 'Изменить',
                    'url'   => ['update', 'id' => $model->id],
                    'ico'   => 'fa fa-pencil',
                    'class' => 'btn btn-success',
                ],
                'deleteElementCategory' => [
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
            'name',
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
