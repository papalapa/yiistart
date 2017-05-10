<?php

    use papalapa\yiistart\modules\i18n\models\SourceMessage;
    use papalapa\yiistart\widgets\BootstrapActiveForm;
    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model SourceMessage */
    /* @var $form yii\widgets\ActiveForm */
?>

<div class="message-form">

    <?php $form = BootstrapActiveForm::begin(); ?>

    <?= $form->field($model, 'message')->textInput(['disabled' => 'disabled', 'maxlength' => true]) ?>

    <?php foreach ($model->messages as $language => $message) : ?>
        <div class="four wide column">
            <?= $form->field($model->messages[$language], '[' . $language . ']translation')->label($language) ?>
        </div>
    <?php endforeach; ?>

    <div class="form-group">
        <?= Html::submitButton(Html::tag('i', null, ['class' => 'fa fa-save']) . ' ' . ($model->isNewRecord ? 'Создать' : 'Изменить'),
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php BootstrapActiveForm::end(); ?>

</div>
