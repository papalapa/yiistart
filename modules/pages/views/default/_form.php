<?php

    use backend\widgets\CKEditor;
    use backend\widgets\ElfinderImageInput;
    use common\models\i18n;
    use common\models\User;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;

    /* @var $this yii\web\View */
    /* @var $model common\models\Pages */
    /* @var $form yii\widgets\ActiveForm */
?>

<div class="pages-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="row">
        <div class="col-lg-8 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Основные данные</h3>
                </div>
                <div class="panel-body">
                    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

                    <?
                        echo $form->field($model, 'header')->textInput(['maxlength' => true]);
                        foreach (i18n::locales() as $locale) {
                            if (Yii::$app->language <> $locale) {
                                echo $form->field($model, 'header_' . $locale)->textInput(['maxlength' => true]);
                            }
                        }

                        if ($model->contextable) {
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

                        if ($model->imagable) {
                            echo $form->field($model, 'img')->widget(ElfinderImageInput::className(), [
                                'controller' => 'file-upload',
                                'filter'     => 'image',
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
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Изменить',
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
