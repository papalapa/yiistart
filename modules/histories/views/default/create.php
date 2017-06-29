<?php

    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model \papalapa\yiistart\modules\histories\models\Histories */

    $this->title                   = 'Создание события';
    $this->params['breadcrumbs'][] = ['label' => 'События', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="history-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
