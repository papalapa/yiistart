<?php

    use papalapa\yiistart\modules\subscribe\models\Dispatches;
    use papalapa\yiistart\widgets\BootstrapActiveForm;
    use papalapa\yiistart\widgets\CKEditor;
    use papalapa\yiistart\widgets\DateTimePicker;
    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model papalapa\yiistart\modules\subscribe\models\Dispatches */
    /* @var $form yii\widgets\ActiveForm */
?>

<div class="subscriptions-form">

    <?php $form = BootstrapActiveForm::begin(); ?>

    <?= $form->field($model, 'subject')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'html')->widget(CKEditor::className()) ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'start_at')->widget(DateTimePicker::className()) ?>

    <?= $form->field($model, 'status')->radioList(Dispatches::statuses()) ?>

    <hr />

    <div class="form-group">
        <?= Html::submitButton(Html::tag('i', null, ['class' => 'fa fa-check']) . ' ' . ($model->isNewRecord ? 'Создать' : 'Изменить'),
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php BootstrapActiveForm::end(); ?>

</div>
