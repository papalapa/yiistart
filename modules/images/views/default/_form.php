<?php

    use kartik\select2\Select2;
    use papalapa\yiistart\modules\i18n\models\i18n;
    use papalapa\yiistart\modules\images\models\ImageCategory;
    use papalapa\yiistart\modules\users\models\BaseUser;
    use papalapa\yiistart\widgets\BootstrapActiveForm;
    use papalapa\yiistart\widgets\ElfinderImageInput;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model \papalapa\yiistart\modules\images\models\Images */
    /* @var $form yii\widgets\ActiveForm */
?>

<div class="images-form">

    <?php $form = BootstrapActiveForm::begin(); ?>

    <?
        $query = ImageCategory::find()->select(['id', 'name']);
        if (Yii::$app->user->identity->role <> BaseUser::ROLE_DEVELOPER) {
            $query->andWhere(['is_visible' => true]);
        }
        $categories = $query->orderBy(['name' => SORT_ASC])->asArray()->all();
        echo $form->field($model, 'category_id')->widget(Select2::className(), [
            'data'          => ArrayHelper::map($categories, 'id', 'name'),
            'options'       => [
                'placeholder' => 'Выберите категорию',
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ]);
    ?>

    <?
        echo $form->field($model, 'title')->textInput(['maxlength' => true]);
        foreach (i18n::locales() as $locale) {
            if (Yii::$app->language <> $locale) {
                echo $form->field($model, 'title_'.$locale)->textInput(['maxlength' => true]);
            }
        }
    ?>

    <?
        echo $form->field($model, 'text')->textarea(['rows' => 3, 'maxlength' => true]);
        foreach (i18n::locales() as $locale) {
            if (Yii::$app->language <> $locale) {
                echo $form->field($model, 'text_'.$locale)->textarea(['rows' => 3, 'maxlength' => true]);
            }
        }
    ?>

    <?
        echo $form->field($model, 'image')->widget(ElfinderImageInput::className(), [
            'filter' => 'image',
        ]);

        if ($model->isNewRecord && !$model->hasErrors()) {
            $model->order = $model::find()->select(['[[order]]'])->max('[[order]]') + 1;
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
