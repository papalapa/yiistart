<?php

    use papalapa\yiistart\widgets\BootstrapActiveForm;
    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model papalapa\yiistart\modules\i18n\models\SourceMessageCategories */
    /* @var $form papalapa\yiistart\widgets\BootstrapActiveForm */
?>

<div class="source-message-categories-form">

    <?php $form = BootstrapActiveForm::begin(); ?>

    <?= $form->field($model, 'category')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'translate')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Html::tag('i', null, ['class' => 'fa fa-check']).' '.($model->isNewRecord
                ? 'Создать' : 'Изменить'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php BootstrapActiveForm::end(); ?>

</div>
