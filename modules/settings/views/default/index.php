<?php

    use papalapa\yiistart\widgets\ControlButtonsPanel;
    use papalapa\yiistart\widgets\GridDateColumn;
    use papalapa\yiistart\widgets\GridIntegerPkColumn;
    use papalapa\yiistart\widgets\GridToggleColumn;
    use papalapa\yiistart\widgets\GridUserEmailColumn;
    use yii\grid\GridView;
    use yii\helpers\Html;
    use yii\widgets\Pjax;

    /* @var $this yii\web\View */
    /* @var $searchModel papalapa\yiistart\modules\settings\models\SettingsSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */

    $this->title                   = 'Настройки';
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="settings-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?
        echo ControlButtonsPanel::widget([
            'items' => [
                'createSetting' => [
                    'title' => 'Создать',
                    'url'   => ['create'],
                    'class' => 'btn btn-success',
                ],
            ],
        ]);
    ?>

    <?php Pjax::begin(); ?>

    <?
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns'      => [
                // ['class' => 'yii\grid\SerialColumn'],

                [
                    'class'     => GridIntegerPkColumn::className(),
                    'attribute' => 'id',
                ],
                'key',
                'value:ntext',
                [
                    'class'      => GridToggleColumn::className(),
                    'attribute'  => 'is_active',
                    'labelTitle' => 'Активность',
                    'labelIco'   => 'fa fa-eye',
                ],
                [
                    'class'     => GridUserEmailColumn::className(),
                    'attribute' => 'created_by',
                ],
                [
                    'class'     => GridUserEmailColumn::className(),
                    'attribute' => 'updated_by',
                ],
                [
                    'class'     => GridDateColumn::className(),
                    'attribute' => 'created_at',
                ],
                [
                    'class'     => GridDateColumn::className(),
                    'attribute' => 'updated_at',
                ],

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]);
    ?>

    <?php Pjax::end(); ?>
</div>
