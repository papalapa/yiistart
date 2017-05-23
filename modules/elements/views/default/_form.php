<?php

    use kartik\select2\Select2;
    use papalapa\yiistart\modules\elements\models\ElementCategory;
    use papalapa\yiistart\modules\elements\models\Elements;
    use papalapa\yiistart\modules\i18n\models\i18n;
    use papalapa\yiistart\modules\users\models\BaseUser;
    use papalapa\yiistart\widgets\BootstrapActiveForm;
    use papalapa\yiistart\widgets\CKEditor;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model papalapa\yiistart\modules\elements\models\Elements */
    /* @var $form yii\widgets\ActiveForm */

?>

<div class="elements-form">

    <?php $form = BootstrapActiveForm::begin(); ?>

    <? echo $form->errorSummary($model) ?>

    <?
        if (Yii::$app->user->identity->role == BaseUser::ROLE_DEVELOPER) {
            $categories = ElementCategory::find()->select(['id', 'name'])->orderBy(['name' => SORT_ASC])->asArray()->all();
            echo $form->field($model, 'category_id')->widget(Select2::className(), [
                'data'          => ArrayHelper::map($categories, 'id', 'name'),
                'options'       => [
                    'placeholder' => 'Выберите категорию',
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]);
            echo $form->field($model, 'alias')->textInput(['maxlength' => true]);
            echo $form->field($model, 'name')->textInput(['maxlength' => true]);
            echo $form->field($model, 'format')->radioList(Elements::formats());
            echo $form->field($model, 'description')->textarea(['rows' => 2, 'maxlength' => true]);
        } else {
            echo Html::tag('div', $model->description, ['class' => 'well']);
        }
    ?>

    <?
        echo $form->field($model, 'text')->widget(CKEditor::className());
        foreach (i18n::locales() as $locale) {
            if (Yii::$app->language <> $locale) {
                echo $form->field($model, 'text_'.$locale)->widget(CKEditor::className());
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
