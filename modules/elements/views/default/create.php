<?php

    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model papalapa\yiistart\modules\elements\models\Elements */

    $this->title                   = 'Создание элемента';
    $this->params['breadcrumbs'][] = ['label' => 'Элементы', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="elements-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
