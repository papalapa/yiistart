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
                        'allowClear' => false,
                    ],
                ]);
                echo $form->field($model, 'alias')->textInput(['maxlength' => true]);
                echo $form->field($model, 'name')->textInput(['maxlength' => true]);
                echo $form->field($model, 'format')->radioList(Elements::formats());
            } else {
                echo $form->field($model, 'format')->radioList(Elements::formats(), ['class' => 'hide']);
            }
        ?>

        <ul class="nav nav-tabs<?= count(i18n::locales()) == 1 ? ' hidden' : null ?>">
            <? foreach (i18n::locales() as $locale): ?>
                <li class="<?= Yii::$app->language == $locale ? 'active' : null ?>">
                    <a href="#lang-<?= $locale ?>" data-toggle="tab"><?= $locale ?></a>
                </li>
            <? endforeach; ?>
        </ul>
        <br />
        <div class="tab-content">
            <div class="tab-pane active" id="lang-<?= Yii::$app->language ?>">
                <?= $form->field($model, 'text')->widget(CKEditor::className(), [
                    'clientOptions' => [
                        // config.enterMode = 2 (do not wrap in P)
                        'enterMode'      => 2,
                        // config.allowedContent = true (do not cleanup html after switching mode)
                        'allowedContent' => true,
                    ],
                    'options'       => ['class' => 'form-control ck-editor'],
                ]) ?>
            </div>
            <? foreach (i18n::locales() as $locale): ?>
                <? if (Yii::$app->language <> $locale): ?>
                    <div class="tab-pane" id="lang-<?= $locale ?>">
                        <?= $form->field($model, 'text_'.$locale)->widget(CKEditor::className(),
                            // config.enterMode = 2 (do not wrap in P)
                            ['clientOptions' => ['enterMode' => 2, 'allowedContent' => true], 'options' => ['class' => 'form-control ck-editor']]) ?>
                    </div>
                <? endif; ?>
            <? endforeach; ?>
        </div>

        <br />

        <?= $form->field($model, 'is_active')->checkbox() ?>

        <hr />

        <div class="form-group">
            <?= Html::submitButton(Html::tag('i', null, ['class' => 'fa fa-check']).' '.($model->isNewRecord ? 'Создать' : 'Изменить'),
                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php BootstrapActiveForm::end(); ?>

    </div>
<?
    $radio = preg_replace('~(\[|\])~', "\\\\\\\\$1", Html::getInputName($model, 'format'));
    $this->registerJs("
        /**
        * Enabling and disabling CKEditor on change value format 
        */
        $(document).on('change', '[name={$radio}]', function(){
            var html = ($(this).val() == 'html');
            $('.ck-editor').each(function(){
                var editor = CKEDITOR.instances[$(this).attr('id')];
                if (editor){
                    editor.destroy(true);
                }
                if (html){
                    editor = CKEDITOR.replace($(this).attr('id'));
                    editor.config.enterMode = CKEDITOR.ENTER_BR; // 2
                    editor.config.allowedContent = true;
                }
            });
        });
        
        /**
        * Once document load event disable CKEditors on non-html formats 
        */
        if ($('[name={$radio}]:checked').val() !== 'html'){
            var CKEDITOR_loaded = false;
            CKEDITOR.on('instanceReady', function(){
                if (!CKEDITOR_loaded){
                    for(var name in CKEDITOR.instances){
                        CKEDITOR.instances[name].destroy(true);
                    }
                }
                CKEDITOR_loaded = true;
            });                
        }
    ");
