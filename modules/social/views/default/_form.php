<?php

    use kartik\select2\Select2;
    use papalapa\yiistart\modules\social\models\Social;
    use papalapa\yiistart\widgets\BootstrapActiveForm;
    use papalapa\yiistart\widgets\ElfinderImageInput;
    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model papalapa\yiistart\modules\social\models\Social */
    /* @var $form papalapa\yiistart\widgets\BootstrapActiveForm */
?>

<div class="social-form">

    <?php $form = BootstrapActiveForm::begin(); ?>

    <?= $form->field($model, 'position')->widget(Select2::className(), [
        'data' => Social::positions(),
    ]) ?>

    <div class="row">
        <div class="col-lg-6 col-md-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-6 col-md-6">
            <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <?= $form->field($model, 'image')->widget(ElfinderImageInput::className()) ?>

    <div class="row">
        <div class="col-lg-6 col-md-6">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-6 col-md-6">
            <?= $form->field($model, 'alt')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <?
        if ($model->isNewRecord && !$model->hasErrors()) {
            $model->order = $model::find()->select(['[[order]]'])->max('[[order]]') + 1;
        }
        echo $form->field($model, 'order')->textInput(['type' => 'number']);
    ?>

    <br />

    <?= $form->field($model, 'is_active')->textInput() ?>

    <hr />

    <div class="form-group">
        <?= Html::submitButton(Html::tag('i', null, ['class' => 'fa fa-check']).' '.($model->isNewRecord
                ? 'Создать' : 'Изменить'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php BootstrapActiveForm::end(); ?>

</div>
