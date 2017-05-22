<?php

    use papalapa\yiistart\modules\elements\models\ElementCategory;
    use papalapa\yiistart\modules\elements\models\Elements;
    use papalapa\yiistart\widgets\ControlButtonsPanel;
    use papalapa\yiistart\widgets\GridActionColumn;
    use papalapa\yiistart\widgets\GridIntegerColumn;
    use papalapa\yiistart\widgets\GridToggleColumn;
    use yii\grid\GridView;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\widgets\Pjax;

    /* @var $this yii\web\View */
    /* @var $searchModel papalapa\yiistart\modules\elements\models\ElementsSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */

    $this->title                   = 'Элементы';
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="elements-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <h4>Изменения вступают в силу в течение <?= ArrayHelper::getValue(Yii::$app->params, 'cache.duration.element', 0) ?> сек.</h4>

    <?
        echo ControlButtonsPanel::widget([
            'items' => [
                'createElement'        => [
                    'title' => 'Создать',
                    'url'   => ['create'],
                    'ico'   => 'fa fa-plus-circle',
                    'class' => 'btn btn-success',
                ],
                'indexElementCategory' => [
                    'title' => 'Категории',
                    'url'   => ['category/index'],
                    'ico'   => 'fa fa-th-large',
                    'class' => 'btn btn-default',
                ],
            ],
        ]);
    ?>

    <?php Pjax::begin(['id' => 'pjax-elements-index', 'options' => ['class' => 'pjax-spinner'], 'timeout' => 10000]); ?>

    <?
        $categories = ElementCategory::find()->select(['id', 'name'])->orderBy(['name' => SORT_ASC])->asArray()->indexBy('id')->all();

        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns'      => [
                //['class' => 'yii\grid\SerialColumn'],
                [
                    'class'     => GridIntegerColumn::className(),
                    'attribute' => 'id',
                ],
                'alias',
                [
                    'attribute' => 'category_id',
                    'filter'    => ArrayHelper::map($categories, 'id', 'name'),
                    'content'   => function ($model) /* @var Elements $model */ {
                        return $model->category->name;
                    },
                ],
                'name',
                [
                    'attribute' => 'format',
                    'filter'    => Elements::formats(),
                    'content'   => function ($model) /* @var Elements $model */ {
                        return ArrayHelper::getValue(Elements::formats(), $model->format);
                    },
                ],
                [
                    'attribute' => 'text',
                    'format'    => 'html',
                ],
                // 'description',
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
                        'view'   => 'viewElement',
                        'update' => 'updateElement',
                        'delete' => 'deleteElement',
                    ],
                ],
            ],
        ]);
    ?>

    <?php Pjax::end(); ?>
</div>
