<?php

    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model \papalapa\yiistart\modules\images\models\Images */

    $this->title                   = 'Создание изображения';
    $this->params['breadcrumbs'][] = ['label' => 'Изображения', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="images-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
