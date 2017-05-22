<?php

    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model papalapa\yiistart\modules\images\models\ImageCategory */

    $this->title                   = 'Создание категории изображений';
    $this->params['breadcrumbs'][] = ['label' => 'Категории изображений', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="image-category-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
