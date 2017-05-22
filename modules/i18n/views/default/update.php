<?php

    use papalapa\yiistart\modules\i18n\models\SourceMessage;
    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model SourceMessage */

    $this->title                   = 'Изменение перевода: ' . $model->message;
    $this->params['breadcrumbs'][] = ['label' => 'Переводы', 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => $model->message, 'url' => ['view', 'id' => $model->id]];
    $this->params['breadcrumbs'][] = 'Изменение';
?>
<div class="message-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
