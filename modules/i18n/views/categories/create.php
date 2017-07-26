<?php

    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model papalapa\yiistart\modules\i18n\models\SourceMessageCategories */

    $this->title                   = 'Создание описания категории переводов';
    $this->params['breadcrumbs'][] = ['label' => 'Описание категорий переводов', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="source-message-categories-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
