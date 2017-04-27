<?php

    use papalapa\yiistart\modules\i18n\models\SourceMessage;
    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model SourceMessage */

    $this->title                   = 'Создание перевода';
    $this->params['breadcrumbs'][] = ['label' => 'Переводы', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="message-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
