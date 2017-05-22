<?php

    use papalapa\yiistart\modules\images\models\Images;
    use papalapa\yiistart\widgets\ControlButtonsPanel;
    use papalapa\yiistart\widgets\GridActionColumn;
    use papalapa\yiistart\widgets\GridToggleColumn;
    use yii\grid\GridView;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\widgets\Pjax;

    /* @var $this yii\web\View */
    /* @var $searchModel \papalapa\yiistart\modules\images\models\ImagesSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */

    $this->title                   = 'Фотографии';
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="yiistart\modules\images-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?
        echo ControlButtonsPanel::widget([
            'items' => [
                'createImage' => [
                    'title' => 'Создать',
                    'url'   => ['create'],
                    'ico'   => 'fa fa-plus-circle',
                    'class' => 'btn btn-success',
                ],
            ],
        ]);
    ?>

    <?php Pjax::begin(['id' => 'pjax-images-index', 'options' => ['class' => 'pjax-spinner'], 'timeout' => 10000]); ?>

    <?
        $siteUrlManager          = clone Yii::$app->urlManager;
        $siteUrlManager->baseUrl = '/';

        $indexNumbers = Images::find()->select(['order'])->orderBy(['order' => SORT_ASC])->asArray()->all();

        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns'      => [
                //['class' => 'yii\grid\SerialColumn'],
                //'id',
                [
                    'attribute' => 'order',
                    'filter'    => ArrayHelper::map($indexNumbers, 'order', 'order'),
                ],
                [
                    'attribute' => 'title',
                    'filter'    => false,
                ],
                // 'text:ntext',
                [
                    'attribute' => 'image',
                    'format'    => 'html',
                    'content'   => function ($model) use ($siteUrlManager) /* @var Images $model */ {
                        $img = $model->image ? Html::img($model->image, ['height' => 40]) : null;

                        return $img ? Html::a($img, $siteUrlManager->createUrl([$model->image]), ['data-pjax' => 0, 'target' => '_blank']) : null;
                    },
                ],
                [
                    'attribute' => 'size',
                    'content'   => function ($model) /* @var Images $model */ {
                        return sprintf('%s МБ', number_format($model->size / 1024 / 1024, 2));
                    },
                ],
                'width',
                'height',
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
                        'view'   => 'viewImage',
                        'update' => 'updateImage',
                        'delete' => 'deleteImage',
                    ],
                ],
            ],
        ]);
    ?>

    <?php Pjax::end(); ?>
</div>
