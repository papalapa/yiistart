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

    <ul class="nav nav-tabs<?= count(i18n::locales()) == 1 ? ' hidden' : null ?>">
        <? foreach (i18n::locales() as $locale): ?>
            <li class="<?= Yii::$app->language == $locale ? 'active' : null ?>">
                <a href="#lang-<?= $locale ?>" data-toggle="tab"><?= $locale ?></a>
            </li>
        <? endforeach; ?>
    </ul>
    <br />
    <div class="tab-content">
        <div class="tab-pane active" id="lang-<?= Yii::$app->language ?>">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'text')->widget(CKEditor::className()) ?>
        </div>
        <? foreach (i18n::locales() as $locale): ?>
            <? if (Yii::$app->language <> $locale): ?>
                <div class="tab-pane" id="lang-<?= $locale ?>">
                    <?= $form->field($model, sprintf('title_%s', $locale))->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, sprintf('text_%s', $locale))->widget(CKEditor::className()) ?>
                </div>
            <? endif; ?>
        <? endforeach; ?>
    </div>

    <br />

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
