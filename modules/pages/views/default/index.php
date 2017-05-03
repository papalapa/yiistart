<?php

    use papalapa\yiistart\modules\i18n\models\i18n;
    use papalapa\yiistart\modules\pages\models\Pages;
    use papalapa\yiistart\widgets\ControlButtonsPanel;
    use papalapa\yiistart\widgets\GridToggleColumn;
    use yii\grid\GridView;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\widgets\Pjax;

    /* @var $this yii\web\View */
    /* @var $searchModel papalapa\yiistart\modules\pages\models\PagesSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */

    $this->title                   = 'Страницы';
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="pages-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <h4>Изменения вступают в силу в течение <?= ArrayHelper::getValue(Yii::$app->params, 'cache.page.duration', 60) ?> сек.</h4>

    <?
        echo ControlButtonsPanel::widget([
            'items' => [
                'createPage' => [
                    'title' => 'Создать',
                    'url'   => ['create'],
                    'class' => 'btn btn-success',
                ],
            ],
        ]);
    ?>

    <?php Pjax::begin(); ?>

    <?
        $siteUrlManager          = clone (Yii::$app->urlManager);
        $siteUrlManager->baseUrl = '/';

        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns'      => [
                //['class' => 'yii\grid\SerialColumn'],

                'id',
                [
                    'attribute' => 'header_ru',
                    'filter'    => false,
                ],
                [
                    'attribute' => 'header_en',
                    'filter'    => false,
                ],
                // 'text:ntext',
                [
                    'attribute' => 'img',
                    'format'    => 'html',
                    'filter'    => [0 => 'нет', 1 => 'есть'],
                    'content'   => function ($model) {
                        /**
                         * @var Pages $model
                         */
                        return $model->img
                            ? Html::tag('i', null, ['class' => 'text-success fa fa-check'])
                            : Html::tag('i', null, ['class' => 'fa fa-times-circle']);
                    },
                ],
                [
                    'class'      => GridToggleColumn::className(),
                    'attribute'  => 'is_active',
                    'labelTitle' => 'Активность',
                    'labelIco'   => 'fa fa-eye',
                ],
                [
                    'label'       => Html::tag('span', Html::tag('i', null, ['class' => 'fa fa-globe']),
                        ['data-toggle' => 'tooltip', 'title' => 'Мета-теги']),
                    'encodeLabel' => false,
                    'content'     => function ($model) {
                        /**
                         * @var Pages $model
                         */
                        $meta = [];

                        foreach (['title' => 'header', 'description' => 'info', 'keywords' => 'key'] as $tag => $ico) {
                            foreach (i18n::locales() as $locale) {
                                if ($model->{$tag . '_' . $locale}) {
                                    $meta[] = Html::tag('span',
                                        Html::tag('i', null, ['class' => 'fa fa-' . $ico]) . ' | ' . $locale,
                                        ['class' => 'label label-success', 'data-toggle' => 'tooltip', 'title' => sprintf('%s (%s)', $tag, $locale)]);
                                } else {
                                    $meta[] = Html::tag('span',
                                        Html::tag('i', null, ['class' => 'fa fa-' . $ico]) . ' | ' . $locale,
                                        ['class' => 'label label-danger', 'data-toggle' => 'tooltip', 'title' => sprintf('%s (%s)', $tag, $locale)]);
                                }
                            }
                        }

                        return implode(' ', $meta);
                    },
                ],
                // 'created_by',
                // 'updated_by',
                // 'created_at',
                // 'updated_at',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]);
    ?>

    <?php Pjax::end(); ?>
</div>