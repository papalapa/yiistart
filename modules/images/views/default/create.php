<?php

    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model \papalapa\yiistart\modules\photo\models\Photo */

    $this->title                   = 'Создание фотографии';
    $this->params['breadcrumbs'][] = ['label' => 'Фотографии', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="photo-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
