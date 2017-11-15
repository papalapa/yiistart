<?php

    use papalapa\yiistart\widgets\BootstrapActiveForm;
    use papalapa\yiistart\widgets\CKEditor;
    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model papalapa\yiistart\modules\image\models\Album */
    /* @var $form papalapa\yiistart\widgets\BootstrapActiveForm */
?>

<div class="album-form">

    <?php $form = BootstrapActiveForm::begin(); ?>

    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4">
            <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4">
            <?= $form->field($model, 'scale')->textInput(['type' => 'number']) ?>
        </div>
    </div>

    <br />

    <fieldset>
        <legend>Доступность полей</legend>

        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-6">
                <?= $form->field($model, 'has_name')->checkbox() ?>
                <?= $form->field($model, 'has_text')->checkbox() ?>
                <?= $form->field($model, 'has_caption')->checkbox() ?>
                <?= $form->field($model, 'has_alt')->checkbox() ?>
                <?= $form->field($model, 'has_title')->checkbox() ?>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6">
                <?= $form->field($model, 'has_src')->checkbox() ?>
                <?= $form->field($model, 'has_cssclass')->checkbox() ?>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6">
                <?= $form->field($model, 'has_twin')->checkbox() ?>
                <?= $form->field($model, 'has_twin_cssclass')->checkbox() ?>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6">
                <?= $form->field($model, 'has_link')->checkbox() ?>
                <?= $form->field($model, 'has_link_cssclass')->checkbox() ?>
            </div>
        </div>
    </fieldset>

    <br />

    <fieldset>
        <legend>Валидация</legend>

        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6">
                <?= $form->field($model, 'validator_controller')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <?= $form->field($model, 'validator_extensions')->textInput(['maxlength' => true])->hint('Разделитель: «,», «;», « »') ?>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <?= $form->field($model, 'validator_min_size')->textInput(['type' => 'number']) ?>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <?= $form->field($model, 'validator_max_size')->textInput(['type' => 'number']) ?>
            </div>
        </div>
    </fieldset>

    <br />

    <fieldset>
        <legend>Шаблон и описание</legend>

        <?= $form->field($model, 'template')->textarea(['rows' => 3, 'maxlength' => true]) ?>
        <p>
            Параметры для сборки:
            <code>[name]</code>
            <code>[text]</code>
            <code>[caption]</code>
            <code>[src]</code>
            <code>[src_class]</code>
            <code>[twin]</code>
            <code>[twin_class]</code>
            <code>[link]</code>
            <code>[link_class]</code>
        </p>

        <?= $form->field($model, 'description')->widget(CKEditor::className()) ?>
    </fieldset>

    <br />

    <?= $form->field($model, 'is_multilingual_images')->checkbox() ?>

    <?= $form->field($model, 'is_visible')->checkbox() ?>

    <?= $form->field($model, 'is_active')->checkbox() ?>

    <hr />

    <div class="form-group">
        <?= Html::submitButton(Html::tag('i', null, ['class' => 'fa fa-check']).' '.($model->isNewRecord
                ? 'Создать' : 'Изменить'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php BootstrapActiveForm::end(); ?>

</div>
