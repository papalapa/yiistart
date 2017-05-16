<?php

    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model \papalapa\yiistart\modules\photo\models\Photo */

    $this->title                   = 'Изменение фотографии: ' . sprintf('№%s', $model->index_number);
    $this->params['breadcrumbs'][] = ['label' => 'Фотографии', 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => sprintf('№%s', $model->index_number), 'url' => ['view', 'id' => $model->id]];
    $this->params['breadcrumbs'][] = 'Изменение';
?>
<div class="photo-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
