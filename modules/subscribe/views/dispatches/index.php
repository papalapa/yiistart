<?php

    use papalapa\yiistart\modules\subscribe\models\Dispatches;
    use papalapa\yiistart\widgets\ControlButtonsPanel;
    use papalapa\yiistart\widgets\GridActionColumn;
    use papalapa\yiistart\widgets\GridIntegerColumn;
    use yii\grid\GridView;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\widgets\Pjax;

    /* @var $this yii\web\View */
    /* @var $searchModel papalapa\yiistart\modules\subscribe\models\DispatchesSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */

    $this->title                   = 'Рассылка';
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="subscriptions-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?
        echo ControlButtonsPanel::widget([
            'items' => [
                'createDispatch' => [
                    'title' => 'Создать',
                    'url'   => ['create'],
                    'ico'   => 'fa fa-plus-circle',
                    'class' => 'btn btn-success',
                ],
            ],
        ]);
    ?>

    <?php Pjax::begin(['id' => 'pjax-dispatches-index', 'options' => ['class' => 'pjax-spinner'], 'timeout' => 10000]); ?>

    <?
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns'      => [
                //['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'id',
                    'class'     => GridIntegerColumn::className(),
                ],
                'subject',
                //'html:ntext',
                //'text:ntext',
                'start_at',
                [
                    'attribute' => 'status',
                    'filter'    => Dispatches::statuses(),
                    'content'   => function ($model) /* @var $model Dispatches */ {
                        return ArrayHelper::getValue(Dispatches::statuses(), $model->status);
                    },
                ],
                // 'created_at',
                [
                    'class'       => GridActionColumn::className(),
                    'permissions' => [
                        'view'   => 'viewDispatch',
                        'update' => 'updateDispatch',
                        'delete' => 'deleteDispatch',
                    ],
                ],
            ],
        ]);
    ?>

    <?php Pjax::end(); ?>
</div>
