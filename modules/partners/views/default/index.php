<?php

    use papalapa\yiistart\widgets\ControlButtonsPanel;
    use papalapa\yiistart\widgets\GridActionColumn;
    use papalapa\yiistart\widgets\GridImageColumn;
    use papalapa\yiistart\widgets\GridOrderColumn;
    use papalapa\yiistart\widgets\GridToggleColumn;
    use yii\grid\GridView;
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

    <?php Pjax::begin(['id' => 'pjax-partners-index', 'options' => ['class' => 'pjax-spinner table-responsive'], 'timeout' => 10000]); ?>

    <?
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns'      => [
                // ['class' => 'yii\grid\SerialColumn'],
                // 'id',
                'url:url',
                'title',
                'alt',
                [
                    'class'     => GridImageColumn::className(),
                    'attribute' => 'image',
                    'filter'    => false,
                ],
                [
                    'class'      => GridOrderColumn::className(),
                    'attribute'  => 'order',
                    'labelTitle' => 'Порядок',
                    'labelIco'   => 'fa fa-sort',
                ],
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
