<?php

    use papalapa\yiistart\modules\menu\models\Menu;
    use papalapa\yiistart\widgets\ControlButtonsPanel;
    use papalapa\yiistart\widgets\GridActionColumn;
    use papalapa\yiistart\widgets\GridToggleColumn;
    use yii\grid\GridView;
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

    <?php Pjax::begin(); ?>

    <?
        $siteUrlManager          = clone (Yii::$app->urlManager);
        $siteUrlManager->baseUrl = '/';

        $sortNumbers = Menu::find()->select(['sort'])->orderBy(['sort' => SORT_ASC])->column();

        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns'      => [
                //['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'sort',
                    'filter'    => array_combine($sortNumbers, $sortNumbers),
                ],
                [
                    'attribute' => 'position',
                    'filter'    => Menu::positions(),
                    'content'   => function ($model, $key, $index, $column) /* @var Menu $model */ {
                        return $model->position;
                    },
                ],
                [
                    'attribute' => 'title',
                ],
                [
                    'attribute' => 'url',
                    'content'   => function ($model, $key, $index, $column) use ($siteUrlManager) {
                        return Html::a($model->url, $siteUrlManager->createUrl([$model->url]), ['target' => '_blank']);
                    },
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
