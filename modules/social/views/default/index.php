<?php

    use papalapa\yiistart\modules\social\models\Social;
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
    /* @var $searchModel papalapa\yiistart\modules\social\models\SocialSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */

    $this->title                   = 'Социальные сети';
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="social-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= ControlButtonsPanel::widget([
        'items' => [
            'createSocial' => [
                'title' => 'Создать',
                'url'   => ['create'],
                'ico'   => 'fa fa-plus-circle',
                'class' => 'btn btn-success',
            ],
        ],
    ]); ?>

    <?php Pjax::begin(['id' => 'pjax-social-index', 'options' => ['class' => 'pjax-spinner table-responsive'], 'timeout' => 10000]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            //['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            [
                'attribute' => 'position',
                'filter'    => Social::positions(),
                'content'   => function ($model) /* @var $model Social */ {
                    return ArrayHelper::getValue(Social::positions(), $model->position);
                },
            ],
            'url:url',
            [
                'attribute' => 'image',
                'class'     => GridImageColumn::className(),
            ],
            // 'title',
            // 'alt',
            [
                'attribute' => 'order',
                'class'     => GridOrderColumn::className(),
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

            //['class' => 'yii\grid\ActionColumn'],
            [
                'class'       => GridActionColumn::className(),
                'permissions' => [
                    'view'   => 'viewSocial',
                    'update' => 'updateSocial',
                    'delete' => 'deleteSocial',
                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>
