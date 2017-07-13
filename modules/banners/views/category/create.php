<?php

    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model papalapa\yiistart\modules\banners\models\BannersCategory */

    $this->title                   = 'Создание категории баннеров';
    $this->params['breadcrumbs'][] = ['label' => 'Категории баннеров', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="banners-category-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
