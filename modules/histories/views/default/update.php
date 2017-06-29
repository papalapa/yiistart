<?php

    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model \papalapa\yiistart\modules\histories\models\Histories */

    $this->title                   = 'Изменение события: '.$model->title;
    $this->params['breadcrumbs'][] = ['label' => 'События', 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
    $this->params['breadcrumbs'][] = 'Изменение';
?>
<div class="history-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
