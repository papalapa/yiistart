<?php

    use papalapa\yiistart\modules\subscribe\models\Dispatches;
    use papalapa\yiistart\widgets\ControlButtonsPanel;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\widgets\DetailView;

    /* @var $this yii\web\View */
    /* @var $model papalapa\yiistart\modules\subscribe\models\Dispatches */

    $this->title                   = $model->subject;
    $this->params['breadcrumbs'][] = ['label' => 'Рассылка', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="subscriptions-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?
        echo ControlButtonsPanel::widget([
            'items' => [
                'updateDispatch' => [
                    'title' => 'Изменить',
                    'url'   => ['update', 'id' => $model->id],
                    'ico'   => 'fa fa-pencil',
                    'class' => 'btn btn-success',
                ],
                'deleteDispatch' => [
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
            'subject',
            'html:html',
            'text:text',
            'start_at',
            [
                'attribute' => 'status',
                'value'     => ArrayHelper::getValue(Dispatches::statuses(), $model->status),
            ],
            'created_at',
        ],
    ]) ?>

</div>
