<?php

    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model papalapa\yiistart\modules\subscribe\models\Dispatches */

    $this->title                   = 'Создание рассылки';
    $this->params['breadcrumbs'][] = ['label' => 'Рассылка', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="subscriptions-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
