<?php

    use papalapa\yiistart\models\User;
    use papalapa\yiistart\modules\i18n\models\i18n;
    use papalapa\yiistart\widgets\BootstrapActiveForm;
    use papalapa\yiistart\widgets\CKEditor;
    use papalapa\yiistart\widgets\ElfinderImageInput;
    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model papalapa\yiistart\modules\pages\models\Pages */
    /* @var $form BootstrapActiveForm */
?>

<div class="pages-form">

    <?php $form = BootstrapActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'class' => 'bootstrap-active-form']]); ?>

    <div class="row">
        <div class="col-lg-8 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Основные данные</h3>
                </div>
                <div class="panel-body">
                    <?
                        if (User::identity()->role == User::ROLE_DEVELOPER) {
                            echo $form->field($model, 'url')->textInput(['maxlength' => true]);
                        }

                        echo $form->field($model, 'header')->textInput(['maxlength' => true]);
                        foreach (i18n::locales() as $locale) {
                            if (Yii::$app->language <> $locale) {
                                echo $form->field($model, 'header_' . $locale)->textInput(['maxlength' => true]);
                            }
                        }

                        if ($model->isNewRecord || $model->contextable) {
                            echo $form->field($model, 'context')->widget(CKEditor::className());
                            foreach (i18n::locales() as $locale) {
                                if (Yii::$app->language <> $locale) {
                                    echo $form->field($model, 'context_' . $locale)->widget(CKEditor::className());
                                }
                            }

                            echo $form->field($model, 'text')->widget(CKEditor::className());
                            foreach (i18n::locales() as $locale) {
                                if (Yii::$app->language <> $locale) {
                                    echo $form->field($model, 'text_' . $locale)->widget(CKEditor::className());
                                }
                            }
                        }

                        if ($model->isNewRecord || $model->imagable) {
                            echo $form->field($model, 'image')->widget(ElfinderImageInput::className(), [
                                'filter' => 'image',
                            ]);
                        }

                        if (User::identity()->role == User::ROLE_DEVELOPER) {
                            echo $form->field($model, 'contextable')->checkbox();
                            echo $form->field($model, 'imagable')->checkbox();
                        }
                    ?>

                    <?= $form->field($model, 'is_active')->checkbox() ?>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">Мета-теги</h3>
                </div>
                <div class="panel-body">

                    <?php
                        echo $form->field($model, 'title')->textInput(['maxlength' => true]);
                        foreach (i18n::locales() as $locale) {
                            if (Yii::$app->language <> $locale) {
                                echo $form->field($model, 'title_' . $locale)->textInput(['maxlength' => true]);
                            }
                        }

                        echo $form->field($model, 'description')->textInput(['maxlength' => true]);
                        foreach (i18n::locales() as $locale) {
                            if (Yii::$app->language <> $locale) {
                                echo $form->field($model, 'description_' . $locale)->textInput(['maxlength' => true]);
                            }
                        }

                        echo $form->field($model, 'keywords')->textInput(['maxlength' => true]);
                        foreach (i18n::locales() as $locale) {
                            if (Yii::$app->language <> $locale) {
                                echo $form->field($model, 'keywords_' . $locale)->textInput(['maxlength' => true]);
                            }
                        }
                    ?>

                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Html::tag('i', null, ['class' => 'fa fa-save']) . ' ' . ($model->isNewRecord ? 'Создать' : 'Изменить'),
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php BootstrapActiveForm::end(); ?>

</div>
