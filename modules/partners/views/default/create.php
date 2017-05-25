<?php

    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model papalapa\yiistart\modules\partners\models\Partners */

    $this->title                   = 'Создание партнера';
    $this->params['breadcrumbs'][] = ['label' => 'Партнеры', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="partners-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
