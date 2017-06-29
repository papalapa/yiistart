<?php

    use papalapa\yiistart\widgets\ControlButtonsPanel;
    use papalapa\yiistart\widgets\GridActionColumn;
    use papalapa\yiistart\widgets\GridTextColumn;
    use papalapa\yiistart\widgets\GridToggleColumn;
    use yii\grid\GridView;
    use yii\helpers\Html;
    use yii\widgets\Pjax;

    /* @var $this yii\web\View */
    /* @var $searchModel \papalapa\yiistart\modules\histories\models\HistoriesSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */

    $this->title                   = 'События';
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="history-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?
        echo ControlButtonsPanel::widget([
            'items' => [
                'createHistory' => [
                    'title' => 'Создать',
                    'url'   => ['create'],
                    'ico'   => 'fa fa-plus-circle',
                    'class' => 'btn btn-success',
                ],
            ],
        ]);
    ?>

    <?php Pjax::begin(['id' => 'pjax-histories-index', 'options' => ['class' => 'pjax-spinner'], 'timeout' => 10000]); ?>

    <?
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns'      => [
                // ['class' => 'yii\grid\SerialColumn'],
                // 'id',
                'date',
                [
                    'attribute' => 'title',
                    'class'     => GridTextColumn::className(),
                ],
                [
                    'attribute' => 'text',
                    'format'    => 'html',
                    'class'     => GridTextColumn::className(),
                ],
                [
                    'class'      => GridToggleColumn::className(),
                    'attribute'  => 'is_active',
                    'labelTitle' => 'Активность',
                    'labelIco'   => 'fa fa-eye',
                ],
                [
                    'class'       => GridActionColumn::className(),
                    'permissions' => [
                        'view'   => 'viewHistory',
                        'update' => 'updateHistory',
                        'delete' => 'deleteHistory',
                    ],
                ],
            ],
        ]);
    ?>

    <?php Pjax::end(); ?>
</div>
