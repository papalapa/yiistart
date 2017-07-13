<?php

    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model papalapa\yiistart\modules\banners\models\Banners */

    $this->title                   = 'Создание баннера';
    $this->params['breadcrumbs'][] = ['label' => 'Баннеры', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="banners-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
