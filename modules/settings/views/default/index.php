<?php

    use papalapa\yiistart\modules\settings\models\Settings;
    use papalapa\yiistart\widgets\ControlButtonsPanel;
    use papalapa\yiistart\widgets\GridActionColumn;
    use papalapa\yiistart\widgets\GridDateColumn;
    use papalapa\yiistart\widgets\GridIntegerColumn;
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

    <h4>Изменения вступают в силу в течение <?= Settings::paramOf('cache.duration.setting', 0) ?> сек.</h4>

    <?
        echo ControlButtonsPanel::widget([
            'items' => [
                'createSetting' => [
                    'title' => 'Создать',
                    'url'   => ['create'],
                    'ico'   => 'fa fa-plus-circle',
                    'class' => 'btn btn-success',
                ],
            ],
        ]);
    ?>

    <?php Pjax::begin(['id' => 'pjax-settings-index', 'options' => ['class' => 'pjax-spinner'], 'timeout' => 10000]); ?>

    <?
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns'      => [
                // ['class' => 'yii\grid\SerialColumn'],
                [
                    'class'     => GridIntegerColumn::className(),
                    'attribute' => 'id',
                ],
                'title',
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
                    'model'     => $searchModel,
                ],
                [
                    'class'     => GridDateColumn::className(),
                    'attribute' => 'updated_at',
                    'model'     => $searchModel,
                ],
                [
                    'class'       => GridActionColumn::className(),
                    'permissions' => [
                        'view'   => 'viewSetting',
                        'update' => 'updateSetting',
                        'delete' => 'deleteSetting',
                    ],
                ],
            ],
        ]);
    ?>

    <?php Pjax::end(); ?>
</div>
