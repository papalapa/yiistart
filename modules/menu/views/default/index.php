<?php

    use papalapa\yiistart\modules\menu\models\Menu;
    use papalapa\yiistart\widgets\ControlButtonsPanel;
    use papalapa\yiistart\widgets\GridActionColumn;
    use papalapa\yiistart\widgets\GridExistColumn;
    use papalapa\yiistart\widgets\GridOrderColumn;
    use papalapa\yiistart\widgets\GridToggleColumn;
    use yii\grid\GridView;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\widgets\Pjax;

    /* @var $this yii\web\View */
    /* @var $searchModel papalapa\yiistart\modules\menu\models\MenuSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */

    $this->title                   = 'Меню';
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?
        echo ControlButtonsPanel::widget([
            'items' => [
                'createMenu' => [
                    'title' => 'Создать',
                    'url'   => ['create'],
                    'ico'   => 'fa fa-plus-circle',
                    'class' => 'btn btn-success',
                ],
            ],
        ]);
    ?>

    <?php Pjax::begin(['id' => 'pjax-menu-index', 'options' => ['class' => 'pjax-spinner table-responsive'], 'timeout' => 10000]); ?>

    <?
        $siteUrlManager          = clone (Yii::$app->urlManager);
        $siteUrlManager->baseUrl = '/';

        $roots  = Menu::roots();
        $orders = Menu::find()->select(['order'])->orderBy(['order' => SORT_ASC])->column();

        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns'      => [
                //['class' => 'yii\grid\SerialColumn'],
                [
                    'class'      => GridOrderColumn::className(),
                    'attribute'  => 'order',
                    'labelTitle' => 'Порядок',
                    'labelIco'   => 'fa fa-sort',
                ],
                [
                    'attribute' => 'position',
                    'filter'    => Menu::positions(),
                    'content'   => function ($model, $key, $index, $column) /* @var Menu $model */ {
                        return ArrayHelper::getValue(Menu::positions(), $model->position);
                    },
                ],
                [
                    'attribute' => 'title',
                ],
                [
                    'attribute' => 'parent',
                    'filter'    => ArrayHelper::map($roots, 'id', 'title'),
                    'content'   => function ($model, $key, $index, $column) /* @var Menu $model */ use ($roots) {
                        $root = ArrayHelper::getValue($roots, $model->parent);

                        return $root ? $root->title : null;
                    },
                ],
                [
                    'attribute' => 'url',
                    'content'   => function ($model, $key, $index, $column) use ($siteUrlManager) {
                        return Html::a($model->url, $siteUrlManager->createUrl([$model->url]), ['target' => '_blank']);
                    },
                ],
                [
                    'class'       => GridExistColumn::className(),
                    'attribute'   => 'is_static',
                    'label'       => Html::tag('i', null, ['class' => 'fa fa-external-link', 'title' => 'Статичная ссылка', 'data-toggle' => 'tooltip']),
                    'encodeLabel' => false,
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
                        'view'   => 'viewMenu',
                        'update' => 'updateMenu',
                        'delete' => 'deleteMenu',
                    ],
                ],
            ],
        ]);
    ?>

    <?php Pjax::end(); ?>
</div>
