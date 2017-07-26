<?php

    use papalapa\yiistart\widgets\ControlButtonsPanel;
    use papalapa\yiistart\widgets\GridActionColumn;
    use yii\grid\GridView;
    use yii\helpers\Html;
    use yii\widgets\Pjax;

    /* @var $this yii\web\View */
    /* @var $searchModel papalapa\yiistart\modules\i18n\models\SourceMessageCategoriesSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */

    $this->title                   = 'Описание категорий переводов';
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="source-message-categories-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= ControlButtonsPanel::widget([
        'items' => [
            'createSourceMessageCategory' => [
                'title' => 'Создать',
                'url'   => ['create'],
                'ico'   => 'fa fa-plus-circle',
                'class' => 'btn btn-success',
            ],
            'indexTranslation'            => [
                'title' => 'Переводы',
                'url'   => ['/i18n'],
                'ico'   => 'fa fa-language',
                'class' => 'btn btn-default',
            ],
        ],
    ]); ?>

    <?php Pjax::begin(['id' => 'pjax-source-message-categories-index', 'options' => ['class' => 'pjax-spinner table-responsive'], 'timeout' => 10000]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            //['class' => 'yii\grid\SerialColumn'],

            'category',
            'translate',

            //['class' => 'yii\grid\ActionColumn'],
            [
                'class'       => GridActionColumn::className(),
                'permissions' => [
                    'view'   => 'viewSourceMessageCategory',
                    'update' => 'updateSourceMessageCategory',
                    'delete' => 'deleteSourceMessageCategory',
                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>
