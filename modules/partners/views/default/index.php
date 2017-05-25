<?php

    use papalapa\yiistart\modules\partners\models\Partners;
    use papalapa\yiistart\widgets\ControlButtonsPanel;
    use papalapa\yiistart\widgets\GridActionColumn;
    use papalapa\yiistart\widgets\GridOrderColumn;
    use papalapa\yiistart\widgets\GridToggleColumn;
    use yii\grid\GridView;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\widgets\Pjax;

    /* @var $this yii\web\View */
    /* @var $searchModel papalapa\yiistart\modules\partners\models\PartnersSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */

    $this->title                   = 'Партнеры';
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="partners-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?
        echo ControlButtonsPanel::widget([
            'items' => [
                'createPartner' => [
                    'title' => 'Создать',
                    'url'   => ['create'],
                    'ico'   => 'fa fa-plus-circle',
                    'class' => 'btn btn-success',
                ],
            ],
        ]);
    ?>

    <?php Pjax::begin(['id' => 'pjax-partners-index', 'options' => ['class' => 'pjax-spinner'], 'timeout' => 10000]); ?>

    <?
        $siteUrlManager          = clone Yii::$app->urlManager;
        $siteUrlManager->baseUrl = '/';

        $orders = Partners::find()->select(['order'])->orderBy(['order' => SORT_ASC])->asArray()->all();

        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns'      => [
                // ['class' => 'yii\grid\SerialColumn'],
                // 'id',
                [
                    'attribute' => 'order',
                    'class'     => GridOrderColumn::className(),
                    'filter'    => ArrayHelper::map($orders, 'order', 'order'),
                ],
                [
                    'attribute' => 'image',
                    'format'    => 'html',
                    'content'   => function ($model) use ($siteUrlManager) /* @var \papalapa\yiistart\modules\partners\models\Partners $model */ {
                        $img = $model->image ? Html::img($model->image, ['height' => 40]) : null;

                        return $img ? Html::a($img, $siteUrlManager->createUrl([$model->image]), ['data-pjax' => 0, 'target' => '_blank']) : null;
                    },
                ],
                'url:url',
                'title',
                'alt',
                [
                    'class'      => GridToggleColumn::className(),
                    'attribute'  => 'is_active',
                    'labelTitle' => 'Активность',
                    'labelIco'   => 'fa fa-eye',
                ],
                // 'created_by',
                // 'updated_by',
                // 'created_at',
                // 'updated_at',
                [
                    'class'       => GridActionColumn::className(),
                    'permissions' => [
                        'view'   => 'viewPartner',
                        'update' => 'updatePartner',
                        'delete' => 'deletePartner',
                    ],
                ],
            ],
        ]); ?>
    <?php Pjax::end(); ?></div>
