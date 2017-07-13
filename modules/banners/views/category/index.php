<?php

    use papalapa\yiistart\widgets\ControlButtonsPanel;
    use papalapa\yiistart\widgets\GridActionColumn;
    use papalapa\yiistart\widgets\GridToggleColumn;
    use yii\grid\GridView;
    use yii\helpers\Html;
    use yii\widgets\Pjax;

    /* @var $this yii\web\View */
    /* @var $searchModel papalapa\yiistart\modules\banners\models\BannersCategorySearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */

    $this->title                   = 'Категории баннеров';
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="banners-category-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?
        echo ControlButtonsPanel::widget([
            'items' => [
                'createBannerCategory' => [
                    'title' => 'Создать',
                    'url'   => ['create'],
                    'ico'   => 'fa fa-plus-circle',
                    'class' => 'btn btn-success',
                ],
            ],
        ]);
    ?>

    <?php Pjax::begin(['id' => 'pjax-banner-category-index', 'options' => ['class' => 'pjax-spinner table-responsive'], 'timeout' => 10000]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            //['class' => 'yii\grid\SerialColumn'],
            'id',
            'alias',
            'name',
            [
                'class'      => GridToggleColumn::className(),
                'attribute'  => 'is_visible',
                'labelTitle' => 'Видимость',
                'labelIco'   => 'fa fa-check',
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
                    'view'   => 'viewBannerCategory',
                    'update' => 'updateBannerCategory',
                    'delete' => 'deleteBannerCategory',
                ],
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
