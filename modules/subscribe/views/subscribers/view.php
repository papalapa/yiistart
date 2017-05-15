<?php

    use papalapa\yiistart\modules\subscribe\models\Subscribers;
    use papalapa\yiistart\widgets\ControlButtonsPanel;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\widgets\DetailView;

    /* @var $this yii\web\View */
    /* @var $model papalapa\yiistart\modules\subscribe\models\Subscribers */

    $this->title                   = $model->email;
    $this->params['breadcrumbs'][] = ['label' => 'Подписчики', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="subscribers-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?
        echo ControlButtonsPanel::widget([
            'items' => [
                'updateSubscriber' => [
                    'title' => 'Изменить',
                    'url'   => ['update', 'id' => $model->id],
                    'ico'   => 'fa fa-pencil',
                    'class' => 'btn btn-success',
                ],
                'deleteSubscriber' => [
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
            [
                'attribute' => 'status',
                'value'     => ArrayHelper::getValue(Subscribers::statuses(), $model->status),
            ],
            'created_at',
        ],
    ]) ?>

</div>
