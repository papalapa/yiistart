<?php

    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model \papalapa\yiistart\modules\images\models\Images */

    $this->title                   = 'Изменение фотографии: ' . sprintf('№%s', $model->order);
    $this->params['breadcrumbs'][] = ['label' => 'Фотографии', 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => sprintf('№%s', $model->order), 'url' => ['view', 'id' => $model->id]];
    $this->params['breadcrumbs'][] = 'Изменение';
?>
<div class="yiistart\modules\images-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
