<?php

    use kartik\color\ColorInput;
    use kolyunya\yii2\widgets\MapInputWidget;
    use papalapa\yiistart\modules\i18n\models\i18n;
    use papalapa\yiistart\modules\settings\models\Settings;
    use papalapa\yiistart\modules\users\models\BaseUser;
    use papalapa\yiistart\widgets\BootstrapActiveForm;
    use papalapa\yiistart\widgets\CKEditor;
    use papalapa\yiistart\widgets\ElfinderImageInput;
    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model papalapa\yiistart\modules\settings\models\Settings */
    /* @var $form yii\widgets\ActiveForm */
?>

<div class="settings-form">

    <?php $form = BootstrapActiveForm::begin(); ?>

    <?= $form->errorSummary($model) ?>

    <?= $form->field($model, 'title')
             ->textInput(['maxlength' => true, 'placeholder' => 'Название', 'readonly' => Yii::$app->user->identity->role <> BaseUser::ROLE_DEVELOPER]);
    ?>

    <?= $form->field($model, 'key')->textInput(['maxlength' => true, 'readonly' => Yii::$app->user->identity->role <> BaseUser::ROLE_DEVELOPER]); ?>

    <?
        if (Yii::$app->user->identity->role == BaseUser::ROLE_DEVELOPER) {
            echo $form->field($model, 'pattern')->textInput(['maxlength' => true]);
        }
    ?>

    <?php
        switch ($model->type) {
            case Settings::TYPE_BOOLEAN:
                echo $form->field($model, 'value')->checkbox();
                break;
            case Settings::TYPE_TEXT:
                echo $form->field($model, 'value')->textarea(['rows' => 3]);
                break;
            case Settings::TYPE_IMAGE:
                echo $form->field($model, 'value')->widget(ElfinderImageInput::className());
                break;
            case Settings::TYPE_FILE:
                echo $form->field($model, 'value')->widget(ElfinderImageInput::className(), ['filter' => ['application', 'image']]);
                break;
            case Settings::TYPE_HTML:
                echo $form->field($model, 'value')->widget(CKEditor::className());
                break;
            case Settings::TYPE_COLOR:
                echo $form->field($model, 'value')->widget(ColorInput::className(), [
                    'pluginOptions' => [
                        'preferredFormat' => 'rgb',
                    ],
                ]);
                break;
            case Settings::TYPE_MAP:
                echo $form->field($model, 'value')->widget(MapInputWidget::className(), [
                    'key'       => Settings::valueOrParam('google.map.api.key'),
                    'pattern'   => '%latitude%, %longitude%',
                    'latitude'  => 43.2220146,
                    'longitude' => 76.8512485,
                    'zoom'      => 15,
                ]);
                break;
            default:
                echo $form->field($model, 'value')->textInput();
                break;
        }
        if ($model->multilingual) {
            foreach (i18n::locales() as $locale) {
                if (Yii::$app->language <> $locale) {
                    switch ($model->type) {
                        case Settings::TYPE_BOOLEAN:
                            echo $form->field($model, 'value_'.$locale)->checkbox();
                            break;
                        case Settings::TYPE_TEXT:
                            echo $form->field($model, 'value_'.$locale)->textarea(['rows' => 3]);
                            break;
                        case Settings::TYPE_IMAGE:
                            echo $form->field($model, 'value_'.$locale)->widget(ElfinderImageInput::className());
                            break;
                        case Settings::TYPE_FILE:
                            echo $form->field($model, 'value_'.$locale)
                                      ->widget(ElfinderImageInput::className(), ['filter' => ['application', 'image']]);
                            break;
                        case Settings::TYPE_HTML:
                            echo $form->field($model, 'value_'.$locale)->widget(CKEditor::className());
                            break;
                        case Settings::TYPE_COLOR:
                            echo $form->field($model, 'value_'.$locale)->widget(ColorInput::className(), [
                                'pluginOptions' => [
                                    'preferredFormat' => 'rgb',
                                ],
                            ]);
                            break;
                        case Settings::TYPE_MAP:
                            echo $form->field($model, 'value_'.$locale)->widget(MapInputWidget::className(), [
                                'key'       => Settings::valueOrParam('google.map.api.key'),
                                'pattern'   => '%latitude%, %longitude%',
                                'latitude'  => 43.2220146,
                                'longitude' => 76.8512485,
                                'zoom'      => 15,
                            ]);
                            break;
                        default:
                            echo $form->field($model, 'value_'.$locale)->textInput();
                            break;
                    }
                }
            }
        }
    ?>

    <?
        if (Yii::$app->user->identity->role <> BaseUser::ROLE_DEVELOPER && $model->comment) {
            ?>
            <pre><b>Комментарий:</b><br /><?= $model->comment ?></pre>
            <?
        } else {
            echo $form->field($model, 'comment')->textInput(['maxlength' => true]);
        }
    ?>

    <?
        if (Yii::$app->user->identity->role == BaseUser::ROLE_DEVELOPER) {
            echo $form->field($model, 'type')->radioList(Settings::types());
        }
    ?>

    <br />

    <?
        if (Yii::$app->user->identity->role == BaseUser::ROLE_DEVELOPER) {
            echo $form->field($model, 'multilingual')->checkbox();
            echo $form->field($model, 'is_visible')->checkbox();
        }
    ?>

    <?= $form->field($model, 'is_active')->checkbox() ?>

    <hr />

    <div class="form-group">
        <?= Html::submitButton(Html::tag('i', null, ['class' => 'fa fa-check']).' '.($model->isNewRecord ? 'Создать' : 'Изменить'),
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php BootstrapActiveForm::end(); ?>

</div>
