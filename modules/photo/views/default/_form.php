<?php

    use papalapa\yiistart\modules\i18n\models\i18n;
    use papalapa\yiistart\widgets\BootstrapActiveForm;
    use papalapa\yiistart\widgets\ElfinderImageInput;
    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model \papalapa\yiistart\modules\photo\models\Photo */
    /* @var $form yii\widgets\ActiveForm */
?>

<div class="photo-form">

    <?php $form = BootstrapActiveForm::begin(); ?>

    <?
        echo $form->field($model, 'title')->textInput(['maxlength' => true]);
        foreach (i18n::locales() as $locale) {
            if (Yii::$app->language <> $locale) {
                echo $form->field($model, 'title_' . $locale)->textInput(['maxlength' => true]);
            }
        }
    ?>

    <?
        echo $form->field($model, 'text')->textarea(['rows' => 3, 'maxlength' => true]);
        foreach (i18n::locales() as $locale) {
            if (Yii::$app->language <> $locale) {
                echo $form->field($model, 'text_' . $locale)->textarea(['rows' => 3, 'maxlength' => true]);
            }
        }
    ?>

    <?
        echo $form->field($model, 'image')->widget(ElfinderImageInput::className(), [
            'filter' => 'image',
        ]);

        if ($model->isNewRecord && !$model->hasErrors()) {
            $model->index_number = $model::find()->select(['index_number'])->max('index_number') + 1;
        }
        echo $form->field($model, 'index_number')->textInput(['type' => 'number']);
    ?>

    <br />

    <?= $form->field($model, 'is_active')->checkbox() ?>

    <hr />

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Изменить',
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php BootstrapActiveForm::end(); ?>

</div>
