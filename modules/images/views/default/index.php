<?php

    use papalapa\yiistart\modules\images\models\ImageCategory;
    use papalapa\yiistart\modules\images\models\Images;
    use papalapa\yiistart\modules\users\models\BaseUser;
    use papalapa\yiistart\widgets\ControlButtonsPanel;
    use papalapa\yiistart\widgets\GridActionColumn;
    use papalapa\yiistart\widgets\GridImageColumn;
    use papalapa\yiistart\widgets\GridOrderColumn;
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
<div class="images-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?
        echo ControlButtonsPanel::widget([
            'items' => [
                'createImage'        => [
                    'title' => 'Создать',
                    'url'   => ['create'],
                    'ico'   => 'fa fa-plus-circle',
                    'class' => 'btn btn-success',
                ],
                'indexImageCategory' => [
                    'title' => 'Категории',
                    'url'   => ['category/index'],
                    'ico'   => 'fa fa-th-large',
                    'class' => 'btn btn-default',
                ],
            ],
        ]);
    ?>

    <?php Pjax::begin(['id' => 'pjax-images-index', 'options' => ['class' => 'pjax-spinner'], 'timeout' => 10000]); ?>

    <?
        $categoryFind = ImageCategory::find()->select(['id', 'name']);
        if (Yii::$app->user->identity->role <> BaseUser::ROLE_DEVELOPER) {
            $categoryFind->andWhere(['is_visible' => true]);
        }
        $categories = $categoryFind->orderBy(['name' => SORT_ASC])->asArray()->indexBy('id')->all();

        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns'      => [
                //['class' => 'yii\grid\SerialColumn'],
                //'id',
                [
                    'attribute' => 'category_id',
                    'filter'    => ArrayHelper::map($categories, 'id', 'name'),
                    'content'   => function ($model) /* @var Images $model */ {
                        return $model->category->name;
                    },
                ],
                [
                    'class'      => GridOrderColumn::className(),
                    'attribute'  => 'order',
                    'labelTitle' => 'Порядок',
                    'labelIco'   => 'fa fa-sort',
                ],
                [
                    'attribute' => 'title',
                ],
                // 'text:ntext',
                [
                    'class'     => GridImageColumn::className(),
                    'attribute' => 'image',
                    'filter'    => false,
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
