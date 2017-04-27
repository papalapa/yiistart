<?php

    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model common\models\Settings */

    $this->title                   = 'Создание настройки';
    $this->params['breadcrumbs'][] = ['label' => 'Настройки', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="settings-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
