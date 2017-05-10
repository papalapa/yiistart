<?php

    use papalapa\yiistart\modules\elements\models\ElementCategory;
    use papalapa\yiistart\widgets\ControlButtonsPanel;
    use papalapa\yiistart\widgets\GridActionColumn;
    use papalapa\yiistart\widgets\GridIntegerColumn;
    use yii\grid\GridView;
    use yii\helpers\Html;
    use yii\widgets\Pjax;

    /* @var $this yii\web\View */
    /* @var $searchModel papalapa\yiistart\modules\elements\models\ElementCategorySearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */

    $this->title                   = 'Категории элементов';
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="element-category-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?
        echo ControlButtonsPanel::widget([
            'items' => [
                'createElementCategory' => [
                    'title' => 'Создать',
                    'url'   => ['create'],
                    'ico'   => 'fa fa-plus-circle',
                    'class' => 'btn btn-success',
                ],
            ],
        ]);
    ?>

    <?php Pjax::begin(['id' => 'pjax-elements-category-index', 'options' => ['class' => 'pjax-spinner'], 'timeout' => 10000]); ?>

    <?
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns'      => [
                //['class' => 'yii\grid\SerialColumn'],
                [
                    'class'     => GridIntegerColumn::className(),
                    'attribute' => 'id',
                ],
                'name',
                [
                    'label'   => 'Кол-во элементов',
                    'content' => function ($model, $key, $index, $column) /* @var ElementCategory $model */ {
                        return count($model->elements);
                    },
                ],
                [
                    'class'       => GridActionColumn::className(),
                    'permissions' => [
                        'view'   => 'viewElementCategory',
                        'update' => 'updateElementCategory',
                        'delete' => 'deleteElementCategory',
                    ],
                ],
            ],
        ]);
    ?>

    <?php Pjax::end(); ?>
</div>
