<?php

    use papalapa\yiistart\modules\i18n\models\i18n;
    use papalapa\yiistart\modules\i18n\models\SourceMessage;
    use papalapa\yiistart\modules\i18n\models\SourceMessageCategories;
    use papalapa\yiistart\widgets\ControlButtonsPanel;
    use papalapa\yiistart\widgets\GridActionColumn;
    use papalapa\yiistart\widgets\GridTextColumn;
    use yii\grid\GridView;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\widgets\Pjax;

    /* @var $this yii\web\View */
    /* @var $searchModel SourceMessage */
    /* @var $dataProvider yii\data\ActiveDataProvider */

    $this->title                   = 'Переводы';
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="message-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?
        echo ControlButtonsPanel::widget([
            'items' => [
                'createTranslation'          => [
                    'title' => 'Создать',
                    'url'   => ['create'],
                    'ico'   => 'fa fa-plus-circle',
                    'class' => 'btn btn-success',
                ],
                'indexSourceMessageCategory' => [
                    'title' => 'Категории',
                    'url'   => ['/i18n/categories'],
                    'ico'   => 'fa fa-th-large',
                    'class' => 'btn btn-default',
                ],
            ],
        ]);
    ?>

    <?php Pjax::begin(['id' => 'pjax-i18n-index', 'options' => ['class' => 'pjax-spinner table-responsive'], 'timeout' => 10000]); ?>

    <?
        $columns   = [];
        $columns[] = [
            'attribute' => 'category',
            'label'     => 'Категория',
            'filter'    => ArrayHelper::map(SourceMessageCategories::find()->select(['category', 'translate'])
                                       ->where(['AND', ['IS NOT', 'translate', null], ['<>', 'translate', '']])->all(),
                'category', 'translate'),
            'value'     => function ($model) /* @var $model SourceMessage */ {
                return $model->categoryDescription ? $model->categoryDescription->translate : $model->category;
            },
        ];
        $columns[] = [
            'attribute' => 'message',
            'class'     => GridTextColumn::className(),
            'minWidth'  => 100,
            'maxWidth'  => 200,
        ];
        foreach (i18n::locales() as $locale) {
            $columns[] = [
                'attribute' => 'message_'.$locale,
                'class'     => GridTextColumn::className(),
                'minWidth'  => 100,
                'maxWidth'  => 200,
                'label'     => mb_strtoupper($locale),
                'content'   => function ($model) /* @var $model SourceMessage */ use ($locale) {
                    return isset($model->messages[$locale]) ? $model->messages[$locale]->translation : null;
                },
            ];
        }
        $columns[] = [
            'attribute' => 'is_translated',
            'label'     => 'Переведено',
            'filter'    => [0 => 'нет', 1 => 'да'],
            'format'    => 'html',
            'content'   => function ($model) /* @var $model SourceMessage */ {
                return $model->isTranslated()
                    ? Html::tag('i', null, ['class' => 'text-success fa fa-check'])
                    : Html::tag('i', null, ['class' => 'text-danger fa fa-times-circle']);
            },
        ];
        $columns[] = [
            'class'       => GridActionColumn::className(),
            'permissions' => [
                'view'   => 'viewTranslation',
                'update' => 'updateTranslation',
                'delete' => 'deleteTranslation',
            ],
        ];

        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns'      => $columns,
        ]);
    ?>

    <?php Pjax::end(); ?>
</div>
