<?php

    use papalapa\yiistart\modules\i18n\models\SourceMessage;
    use papalapa\yiistart\widgets\ControlButtonsPanel;
    use papalapa\yiistart\widgets\GridActionColumn;
    use papalapa\yiistart\widgets\GridIntegerColumn;
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
                'createTranslation' => [
                    'title' => 'Создать',
                    'url'   => ['create'],
                    'ico'   => 'fa fa-plus-circle',
                    'class' => 'btn btn-success',
                ],
            ],
        ]);
    ?>

    <?php Pjax::begin(['id' => 'pjax-i18n-index', 'options' => ['class' => 'pjax-spinner table-responsive'], 'timeout' => 10000]); ?>

    <?
        $categories = SourceMessage::find()->select(['category'])->distinct('category')->all();

        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns'      => [
                // ['class' => 'yii\grid\SerialColumn'],
                [
                    'class'     => GridIntegerColumn::className(),
                    'attribute' => 'id',
                ],
                [
                    'attribute' => 'category',
                    'label'     => 'Категория',
                    'filter'    => ArrayHelper::map($categories, 'category', 'category'),
                    'value'     => function ($model) /* @var $model SourceMessage */ {
                        return $model->category;
                    },
                ],
                'message:ntext',
                [
                    'attribute' => 'is_translated',
                    'label'     => 'Переведено',
                    'filter'    => [0 => 'нет', 1 => 'да'],
                    'format'    => 'html',
                    'content'   => function ($model) /* @var $model SourceMessage */ {
                        return $model->isTranslated()
                            ? Html::tag('i', null, ['class' => 'text-success fa fa-check'])
                            : Html::tag('i', null, ['class' => 'text-danger fa fa-times-circle']);
                    },
                ],
                [
                    'class'       => GridActionColumn::className(),
                    'permissions' => [
                        'view'   => 'viewTranslation',
                        'update' => 'updateTranslation',
                        'delete' => 'deleteTranslation',
                    ],
                ],
            ],
        ]);
    ?>

    <?php Pjax::end(); ?>
</div>
