<?php

    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model papalapa\yiistart\modules\i18n\models\SourceMessageCategories */

    $this->title                   = 'Изменение описания категории переводов: '.$model->category;
    $this->params['breadcrumbs'][] = ['label' => 'Описание категорий переводов', 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => $model->category, 'url' => ['view', 'id' => $model->category]];
    $this->params['breadcrumbs'][] = 'Изменение';
?>
<div class="source-message-categories-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
