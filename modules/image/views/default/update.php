<?php

    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model papalapa\yiistart\modules\image\models\Image */

    $this->title                   = 'Изменение изображения: '.($model->name ? : '#'.$model->order);
    $this->params['breadcrumbs'][] = ['label' => 'Изображения', 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => $model->name ? : '#'.$model->order, 'url' => ['view', 'id' => $model->id]];
    $this->params['breadcrumbs'][] = 'Изменение';
?>
<div class="image-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
