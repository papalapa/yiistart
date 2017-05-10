<?php

    use kartik\select2\Select2;
    use papalapa\yiistart\modules\i18n\models\i18n;
    use papalapa\yiistart\modules\menu\models\Menu;
    use papalapa\yiistart\modules\pages\models\Pages;
    use papalapa\yiistart\widgets\BootstrapActiveForm;
    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model papalapa\yiistart\modules\menu\models\Menu */
    /* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-form">

    <?php $form = BootstrapActiveForm::begin(); ?>

    <?
        echo $form->field($model, 'position')->widget(Select2::className(), [
            'data'          => Menu::positions(),
            'options'       => [
                'placeholder' => 'Выберите расположение',
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ]);
    ?>

    <?
        $urls                    = [];
        $siteUrlManager          = clone (\Yii::$app->urlManager);
        $siteUrlManager->baseUrl = '/';

        $pages = Pages::find()->select(['id', 'url', 'header'])->orderBy(['url' => SORT_ASC])->all();
        foreach ($pages as $page) {
            /* @var $page Pages */
            if ($page->url) {
                $urls[$siteUrlManager->createUrl($page->url)] = 'Модульная страница - ' . $page->header;
            } else {
                $urls[$siteUrlManager->createUrl(['/site/page', 'id' => $page->id])] = 'Текстовая страница - ' . $page->header;
            }
        }

        echo $form->field($model, 'url')->widget(Select2::className(), [
            'data'          => $urls,
            'options'       => [
                'placeholder' => 'Выберите расположение',
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ]);
    ?>

    <?php
        echo $form->field($model, 'title')->textInput(['maxlength' => true]);
        foreach (i18n::locales() as $locale) {
            if (Yii::$app->language <> $locale) {
                echo $form->field($model, 'title_' . $locale)->textInput(['maxlength' => true]);
            }
        }
    ?>

    <?php
        if ($model->isNewRecord && !$model->hasErrors()) {
            $model->sort = $model::find()->max('sort') + 1;
        }
        echo $form->field($model, 'sort')->textInput(['type' => 'number']);
    ?>

    <?= $form->field($model, 'is_active')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton(Html::tag('i', null, ['class' => 'fa fa-save']) . ' ' . ($model->isNewRecord ? 'Создать' : 'Изменить'),
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php BootstrapActiveForm::end(); ?>

</div>
