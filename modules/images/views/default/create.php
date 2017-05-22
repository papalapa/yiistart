<?php

    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model \papalapa\yiistart\modules\images\models\Images */

    $this->title                   = 'Создание фотографии';
    $this->params['breadcrumbs'][] = ['label' => 'Фотографии', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="yiistart\modules\images-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
