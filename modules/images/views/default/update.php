<?php

    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model \papalapa\yiistart\modules\images\models\Images */

    $this->title                   = 'Изменение изображения: ' . sprintf('№%s', $model->order);
    $this->params['breadcrumbs'][] = ['label' => 'Изображения', 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => sprintf('№%s', $model->order), 'url' => ['view', 'id' => $model->id]];
    $this->params['breadcrumbs'][] = 'Изменение';
?>
<div class="images-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
