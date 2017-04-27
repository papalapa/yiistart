<?php

    use papalapa\yiistart\modules\i18n\models\SourceMessage;
    use papalapa\yiistart\widgets\ControlButtonsPanel;
    use yii\helpers\Html;
    use yii\widgets\DetailView;

    /* @var $this yii\web\View */
    /* @var $model SourceMessage */

    $this->title                   = $model->message;
    $this->params['breadcrumbs'][] = ['label' => 'Переводы', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="message-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?
        echo ControlButtonsPanel::widget([
            'items' => [
                'updateTranslation' => [
                    'title' => 'Изменить',
                    'url'   => ['update', 'id' => $model->id],
                    'class' => 'btn btn-success',
                ],
                'deleteTranslation' => [
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
            'id',
            'message',
        ],
    ]) ?>

</div>
