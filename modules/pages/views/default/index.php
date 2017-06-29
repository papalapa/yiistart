<?php

    use papalapa\yiistart\modules\settings\models\Settings;
    use papalapa\yiistart\widgets\ControlButtonsPanel;
    use papalapa\yiistart\widgets\GridActionColumn;
    use papalapa\yiistart\widgets\GridMetatagsColumn;
    use papalapa\yiistart\widgets\GridToggleColumn;
    use yii\grid\GridView;
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

    <h4>Изменения вступают в силу в течение <?= Settings::paramOf('cache.duration.page', 0) ?> сек.</h4>

    <?
        echo ControlButtonsPanel::widget([
            'items' => [
                'createPage' => [
                    'title' => 'Создать',
                    'url'   => ['create'],
                    'ico'   => 'fa fa-plus-circle',
                    'class' => 'btn btn-success',
                ],
            ],
        ]);
    ?>

    <?php Pjax::begin(['id' => 'pjax-pages-index', 'options' => ['class' => 'pjax-spinner table-responsive'], 'timeout' => 10000]); ?>

    <?
        $siteUrlManager          = clone (Yii::$app->urlManager);
        $siteUrlManager->baseUrl = '/';

        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns'      => [
                //['class' => 'yii\grid\SerialColumn'],
                'id',
                'header',
                // 'text:ntext',
                // 'context:ntext',
                // 'image',
                [
                    'class'      => GridToggleColumn::className(),
                    'attribute'  => 'is_active',
                    'labelTitle' => 'Активность',
                    'labelIco'   => 'fa fa-eye',
                ],
                [
                    'class' => GridMetatagsColumn::className(),
                ],
                // 'created_by',
                // 'updated_by',
                // 'created_at',
                // 'updated_at',
                [
                    'class'       => GridActionColumn::className(),
                    'permissions' => [
                        'view'   => 'viewPage',
                        'update' => 'updatePage',
                        'delete' => 'deletePage',
                    ],
                ],
            ],
        ]);
    ?>

    <?php Pjax::end(); ?>
</div>
