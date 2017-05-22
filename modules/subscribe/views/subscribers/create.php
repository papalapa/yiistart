<?php

    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model papalapa\yiistart\modules\subscribe\models\Subscribers */

    $this->title                   = 'Создание подписчика';
    $this->params['breadcrumbs'][] = ['label' => 'Подписчики', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="subscribers-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
