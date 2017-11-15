<?php

    use kartik\select2\Select2;
    use papalapa\yiistart\modules\i18n\models\i18n;
    use papalapa\yiistart\modules\image\models\Album;
    use papalapa\yiistart\modules\users\models\BaseUser;
    use papalapa\yiistart\widgets\BootstrapActiveForm;
    use papalapa\yiistart\widgets\CKEditor;
    use papalapa\yiistart\widgets\ElfinderImageInput;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model papalapa\yiistart\modules\image\models\Image */
    /* @var $form papalapa\yiistart\widgets\BootstrapActiveForm */
?>

    <div class="image-form">

        <?php $form = BootstrapActiveForm::begin(); ?>

        <?
            $query = Album::find();
            if (Yii::$app->user->identity->role <> BaseUser::ROLE_DEVELOPER) {
                $query->andWhere(['is_visible' => true]);
            }
            $albums = $query->orderBy(['name' => SORT_ASC])->asArray()->all();
            echo $form->field($model, 'album_id')->widget(Select2::className(), [
                'data'    => ArrayHelper::map($albums, 'id', 'name'),
                'options' => [
                    'placeholder' => 'Выберите категорию',
                    'data-link'   => 'options', // link with div[data-link-with=options]
                    'options'     => array_map(function ($element) /* @var $element Album */ {
                        return [
                            'data-options'     => [
                                'name'              => (bool) $element['has_name'],
                                'text'              => (bool) $element['has_text'],
                                'caption'           => (bool) $element['has_caption'],
                                'link'              => (bool) $element['has_link'],
                                'src'               => (bool) $element['has_src'] && !$element['is_multilingual_images'],
                                'multilingual-src'  => (bool) $element['has_src'] && $element['is_multilingual_images'],
                                'twin'              => (bool) $element['has_twin'] && !$element['is_multilingual_images'],
                                'multilingual-twin' => (bool) $element['has_twin'] && $element['is_multilingual_images'],
                                'alt'               => (bool) $element['has_alt'],
                                'title'             => (bool) $element['has_title'],
                                'cssclass'          => (bool) $element['has_cssclass'],
                                'twin_cssclass'     => (bool) $element['has_twin_cssclass'],
                                'link_cssclass'     => (bool) $element['has_link_cssclass'],
                            ],
                            'data-description' => ['text' => $element['description']],
                        ];
                    }, ArrayHelper::index($albums, 'id')),
                ],
            ]);
        ?>

        <br />

        <div class="hide" data-link-with="options">

            <pre class="hide" data-description-text=""></pre>

            <ul class="nav nav-tabs language-tabs<?= count(i18n::locales()) == 1 ? ' hidden' : null ?>">
                <? foreach (i18n::locales() as $locale): ?>
                    <li class="<?= Yii::$app->language == $locale ? 'active' : null ?>">
                        <a href="#lang-<?= $locale ?>" data-toggle="tab"><?= $locale ?></a>
                    </li>
                <? endforeach; ?>
            </ul>
            <br />
            <div class="tab-content">
                <div class="tab-pane language-tab active" id="lang-<?= Yii::$app->language ?>">
                    <div data-option="name">
                        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                    </div>

                    <div data-option="alt">
                        <?= $form->field($model, 'alt')->textInput(['maxlength' => true]) ?>
                    </div>

                    <div data-option="title">
                        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
                    </div>

                    <div data-option="text">
                        <?= $form->field($model, 'text')->widget(CKEditor::className()) ?>
                    </div>

                    <div data-option="caption">
                        <?= $form->field($model, 'caption')->widget(CKEditor::className()) ?>
                    </div>

                    <div data-option="multilingual-src">
                        <?= $form->field($model, 'src')->widget(ElfinderImageInput::className()) ?>
                    </div>

                    <div data-option="multilingual-twin">
                        <?= $form->field($model, 'twin')->widget(ElfinderImageInput::className()) ?>
                    </div>
                </div>
                <? foreach (i18n::locales() as $locale): ?>
                    <? if (Yii::$app->language <> $locale): ?>
                        <div class="tab-pane language-tab" id="lang-<?= $locale ?>">
                            <div data-option="name">
                                <?= $form->field($model, 'name_'.$locale)->textInput(['maxlength' => true]) ?>
                            </div>

                            <div data-option="alt">
                                <?= $form->field($model, 'alt_'.$locale)->textInput(['maxlength' => true]) ?>
                            </div>

                            <div data-option="title">
                                <?= $form->field($model, 'title_'.$locale)->textInput(['maxlength' => true]) ?>
                            </div>

                            <div data-option="text">
                                <?= $form->field($model, 'text_'.$locale)->widget(CKEditor::className()) ?>
                            </div>

                            <div data-option="caption">
                                <?= $form->field($model, 'caption_'.$locale)->widget(CKEditor::className()) ?>
                            </div>

                            <div data-option="multilingual-src">
                                <?= $form->field($model, 'src_'.$locale)->widget(ElfinderImageInput::className()) ?>
                            </div>

                            <div data-option="multilingual-twin">
                                <?= $form->field($model, 'twin_'.$locale)->widget(ElfinderImageInput::className()) ?>
                            </div>
                        </div>
                    <? endif; ?>
                <? endforeach; ?>
            </div>

            <hr />

            <div data-option="src">
                <?= $form->field($model, 'src')->widget(ElfinderImageInput::className()) ?>
            </div>

            <div data-option="cssclass">
                <?= $form->field($model, 'cssclass')->textInput(['maxlength' => true]) ?>
            </div>

            <div data-option="twin">
                <?= $form->field($model, 'twin')->widget(ElfinderImageInput::className()) ?>
            </div>

            <div data-option="twin_cssclass">
                <?= $form->field($model, 'twin_cssclass')->textInput(['maxlength' => true]) ?>
            </div>

            <div data-option="link">
                <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>
            </div>

            <div data-option="link_cssclass">
                <?= $form->field($model, 'link_cssclass')->textInput(['maxlength' => true]) ?>
            </div>

            <br />

            <?
                if ($model->isNewRecord && !$model->hasErrors()) {
                    $model->order = $model::find()->select(['[[order]]'])->max('[[order]]') + 1;
                }
                echo $form->field($model, 'order')->textInput(['type' => 'number']);
            ?>

            <br />

            <?= $form->field($model, 'is_active')->checkbox() ?>

            <hr />

            <div class="form-group">
                <?= Html::submitButton(Html::tag('i', null, ['class' => 'fa fa-check']).' '.($model->isNewRecord
                        ? 'Создать' : 'Изменить'), [
                    'class' => $model->isNewRecord
                        ? 'btn btn-success' : 'btn btn-primary',
                ]) ?>
            </div>
        </div>

        <?php BootstrapActiveForm::end(); ?>

    </div>

<?
    $this->registerJs("
        $('[data-link=options]').on('select2:selecting', function(){
            // hide all of options on selecting
            $('[data-link-with=options]').addClass('hide');
        }).on('select2:select', function(){
            var container = $('[data-link-with=options]'); 
            var options = $(this).find('option:selected').data('options');
            var description = $(this).find('option:selected').data('description');
            // hide or show options
            for (var key in options){
                container.find('[data-option=' + key + ']').toggleClass('hide', !options[key]);
            }
            // show description if exists
            $('[data-description-text]').html(description.text).toggleClass('hide', !description.text);
            // hide language tabs if active options are not exists
            $('.language-tabs').toggleClass('hide', container.find('.language-tab [data-option]:not(.hide)').length === 0);
            // show options
            container.removeClass('hide');
        }).trigger('select2:select');
    ");
