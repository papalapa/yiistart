<?php

    use kartik\depdrop\DepDrop;
    use kartik\select2\Select2;
    use papalapa\yiistart\modules\i18n\models\i18n;
    use papalapa\yiistart\modules\menu\models\Menu;
    use papalapa\yiistart\modules\pages\models\Pages;
    use papalapa\yiistart\widgets\BootstrapActiveForm;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\helpers\Url;

    /* @var $this yii\web\View */
    /* @var $model papalapa\yiistart\modules\menu\models\Menu */
    /* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-form">

    <?php $form = BootstrapActiveForm::begin(); ?>

    <?= $form->field($model, 'position')->widget(Select2::className(), [
        'data'          => Menu::positions(),
        'options'       => [
            'placeholder' => 'Выберите расположение',
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ])->hint('Расположение для вложенных пунктов меню при сохранении автоматически принимает позицию родительской ссылки') ?>

    <?
        $parents = [];
        if ($model->position) {
            $parents = Menu::find()->andFilterWhere(['<>', 'id', $model->id])
                           ->andWhere(['position' => $model->position])
                           ->andWhere(['<', 'level', Menu::maxLevelOf($model->position)])
                           ->orderBy(['parent_id' => SORT_ASC, 'level' => SORT_ASC, 'name' => SORT_ASC])
                           ->asArray()->indexBy('id')->all();

            foreach ($parents as $id => $parent) {
                $parents[$id] = [
                    'id'   => $id,
                    'name' => sprintf('%s %s', str_repeat('—', $parent['level']), $parent['name']),
                ];
            }
        }
    ?>
    <?= $form->field($model, 'parent_id')->widget(DepDrop::className(), [
        'data'           => ArrayHelper::map($parents, 'id', 'name'),
        'type'           => DepDrop::TYPE_SELECT2,
        'pluginOptions'  => [
            'depends'     => [Html::getInputId($model, 'position')],
            'url'         => Url::to(['position-parents', 'id' => $model->id]),
            'placeholder' => 'Выберите вложенность',
        ],
        'select2Options' => [
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ],
    ]) ?>

    <?
        $urls                    = [];
        $siteUrlManager          = clone (\Yii::$app->urlManager);
        $siteUrlManager->baseUrl = '/';

        $pages = Pages::find()->select(['id', 'url', 'header'])->orderBy(['url' => SORT_ASC])->all();
        foreach ($pages as $page) {
            /* @var $page Pages */
            if ($page->url) {
                $urls[$siteUrlManager->createUrl($page->url)] = 'Модульная страница - '.$page->header;
            } else {
                $urls[$siteUrlManager->createUrl(['/site/page', 'id' => $page->id])] = 'Текстовая страница - '.$page->header;
            }
        }

        echo $form->field($model, 'url')->widget(Select2::className(), [
            'data'          => ArrayHelper::merge([$model->url => $model->url], $urls),
            'options'       => [
                'placeholder' => 'Выберите страницу',
            ],
            'pluginOptions' => [
                'tags'       => true,
                'allowClear' => true,
            ],
        ])->hint('Для указания статичной ссылки отметьте соответствующую опцию:');
    ?>

    <?= $form->field($model, 'is_static')->checkbox() ?>

    <?php
        echo $form->field($model, 'name')->textInput(['maxlength' => true]);
        foreach (i18n::locales() as $locale) {
            if (Yii::$app->language <> $locale) {
                echo $form->field($model, 'name_'.$locale)->textInput(['maxlength' => true]);
            }
        }
    ?>

    <?php
        if ($model->isNewRecord && !$model->hasErrors()) {
            $model->order = $model::find()->max('[[order]]') + 1;
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
