<?php

    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model papalapa\yiistart\modules\subscribe\models\Subscribers */

    $this->title                   = 'Изменение подписчика: ' . $model->email;
    $this->params['breadcrumbs'][] = ['label' => 'Подписчики', 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => $model->email, 'url' => ['view', 'id' => $model->id]];
    $this->params['breadcrumbs'][] = 'Изменение';
?>
<div class="subscribers-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
