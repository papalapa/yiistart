<?php

    use papalapa\yiistart\modules\i18n\models\i18n;
    use papalapa\yiistart\widgets\BootstrapActiveForm;
    use papalapa\yiistart\widgets\CKEditor;
    use papalapa\yiistart\widgets\DateTimePicker;
    use papalapa\yiistart\widgets\ElfinderImageInput;
    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model \papalapa\yiistart\modules\histories\models\Histories */
    /* @var $form yii\widgets\ActiveForm */
?>

<div class="history-form">

    <?php $form = BootstrapActiveForm::begin(); ?>

    <?= $form->field($model, 'date')->widget(DateTimePicker::className())->hint('Примечание: не может быть двух одинаковых дат') ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <? foreach (i18n::locales() as $locale): ?>
        <? if (Yii::$app->language <> $locale): ?>
            <?= $form->field($model, sprintf('title_%s', $locale))->textInput(['maxlength' => true]) ?>
        <? endif; ?>
    <? endforeach; ?>

    <?= $form->field($model, 'text')->widget(CKEditor::className()) ?>
    <? foreach (i18n::locales() as $locale): ?>
        <? if (Yii::$app->language <> $locale): ?>
            <?= $form->field($model, sprintf('text_%s', $locale))->widget(CKEditor::className()) ?>
        <? endif; ?>
    <? endforeach; ?>

    <?= $form->field($model, 'image')->widget(ElfinderImageInput::className()) ?>

    <br />

    <?= $form->field($model, 'is_active')->checkbox() ?>

    <hr />

    <div class="form-group">
        <?= Html::submitButton(Html::tag('i', null, ['class' => 'fa fa-check']).' '.($model->isNewRecord ? 'Создать' : 'Изменить'),
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php BootstrapActiveForm::end(); ?>

</div>
