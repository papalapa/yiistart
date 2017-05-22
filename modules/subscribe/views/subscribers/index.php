<?php

    use papalapa\yiistart\modules\subscribe\models\Subscribers;
    use papalapa\yiistart\widgets\ControlButtonsPanel;
    use papalapa\yiistart\widgets\GridActionColumn;
    use papalapa\yiistart\widgets\GridIntegerColumn;
    use papalapa\yiistart\widgets\GridToggleColumn;
    use yii\grid\GridView;
    use yii\helpers\Html;
    use yii\widgets\Pjax;

    /* @var $this yii\web\View */
    /* @var $searchModel papalapa\yiistart\modules\subscribe\models\SubscribersSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */

    $this->title                   = 'Подписчики';
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="subscribers-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?
        echo ControlButtonsPanel::widget([
            'items' => [
                'createSubscriber' => [
                    'title' => 'Создать',
                    'url'   => ['create'],
                    'ico'   => 'fa fa-plus-circle',
                    'class' => 'btn btn-success',
                ],
            ],
        ]);
    ?>

    <?php Pjax::begin(['id' => 'pjax-subscribers-index', 'options' => ['class' => 'pjax-spinner'], 'timeout' => 10000]); ?>

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
                'email:email',
                [
                    'attribute'  => 'status',
                    'labelTitle' => 'Подписка',
                    'labelIco'   => 'fa fa-envelope-o',
                    'filter'     => Subscribers::statuses(),
                    'class'      => GridToggleColumn::className(),
                ],
                [
                    'attribute' => 'created_at',
                    'filter'    => false,
                ],
                [
                    'class'       => GridActionColumn::className(),
                    'permissions' => [
                        'view'   => 'viewSubscriber',
                        'update' => 'updateSubscriber',
                        'delete' => 'deleteSubscriber',
                    ],
                ],
            ],
        ]);
    ?>

    <?php Pjax::end(); ?>
</div>
