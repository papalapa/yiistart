<?php

    use papalapa\yiistart\modules\i18n\models\i18n;
    use papalapa\yiistart\modules\users\models\BaseUser;
    use papalapa\yiistart\widgets\BootstrapActiveForm;
    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model papalapa\yiistart\modules\settings\models\Settings */
    /* @var $form yii\widgets\ActiveForm */
?>

<div class="settings-form">

    <?php $form = BootstrapActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'placeholder' => 'Название', 'readonly' => Yii::$app->user->identity->role <> BaseUser::ROLE_DEVELOPER]);
    ?>

    <?= $form->field($model, 'key')->textInput(['maxlength' => true, 'readonly' => Yii::$app->user->identity->role <> BaseUser::ROLE_DEVELOPER]); ?>

    <?php
        echo $form->field($model, 'value')->textInput(['maxlength' => true]);
        foreach (i18n::locales() as $locale) {
            if (Yii::$app->language <> $locale) {
                echo $form->field($model, 'value_'.$locale)->textInput(['maxlength' => true]);
            }
        }
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
