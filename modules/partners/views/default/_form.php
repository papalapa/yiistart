<?php

    use papalapa\yiistart\modules\i18n\models\i18n;
    use papalapa\yiistart\modules\partners\models\Partners;
    use papalapa\yiistart\widgets\BootstrapActiveForm;
    use papalapa\yiistart\widgets\ElfinderImageInput;
    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model papalapa\yiistart\modules\partners\models\Partners */
    /* @var $form yii\widgets\ActiveForm */
?>

<div class="partners-form">

    <?php $form = BootstrapActiveForm::begin(); ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?
        echo $form->field($model, 'title')->textInput(['maxlength' => true]);
        foreach (i18n::locales() as $locale) {
            if (Yii::$app->language <> $locale) {
                echo $form->field($model, 'title_'.$locale)->textInput(['maxlength' => true]);
            }
        }
    ?>

    <?
        echo $form->field($model, 'alt')->textInput(['maxlength' => true]);
        foreach (i18n::locales() as $locale) {
            if (Yii::$app->language <> $locale) {
                echo $form->field($model, 'alt_'.$locale)->textInput(['maxlength' => true]);
            }
        }
    ?>

    <?= $form->field($model, 'image')->widget(ElfinderImageInput::className(), ['filter' => ['image', 'application/pdf']]) ?>

    <?
        if ($model->isNewRecord && !$model->errors) {
            $model->order = Partners::find()->max('[[order]]') + 1;
        }
        echo $form->field($model, 'order')->textInput(['type' => 'number']);
    ?>

    <br />

    <?= $form->field($model, 'is_active')->checkbox() ?>

    <hr />

    <div class="form-group">
        <?= Html::submitButton(Html::tag('i', null, ['class' => 'fa fa-check']).' '.($model->isNewRecord ? 'Создать' : 'Изменить'),
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php BootstrapActiveForm::end(); ?>

</div>
