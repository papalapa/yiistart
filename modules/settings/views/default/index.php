<?php

    use papalapa\yiistart\models\User;
    use papalapa\yiistart\modules\settings\models\Settings;
    use papalapa\yiistart\widgets\ControlButtonsPanel;
    use papapala\yiistart\widgets\GridToggleColumn;
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

                'id',
                'key',
                'value:ntext',
                [
                    'class'      => GridToggleColumn::className(),
                    'attribute'  => 'is_active',
                    'labelTitle' => 'Активность',
                    'labelIco'   => 'fa fa-eye',
                ],
                [
                    'attribute' => 'created_by',
                    'content'   => function ($model) {
                        /** @var $model Settings */
                        return $model->created_by ? User::findOne(['id' => $model->created_by])->email : null;
                    },
                    'format'    => 'text',
                ],
                [
                    'attribute' => 'updated_by',
                    'content'   => function ($model) {
                        /** @var $model Settings */
                        return $model->updated_by ? User::findOne(['id' => $model->updated_by])->email : null;
                    },
                    'format'    => 'text',
                ],
                [
                    'attribute' => 'created_at',
                    'content'   => function ($model) {
                        /** @var $model Settings */
                        return Yii::$app->formatter->asDate($model->created_at, 'd MMMM YYYY, HH:mm');
                    },
                    'format'    => 'raw',
                ],
                [
                    'attribute' => 'updated_at',
                    'content'   => function ($model) {
                        /** @var $model Settings */
                        return Yii::$app->formatter->asDate($model->updated_at, 'd MMMM YYYY, HH:mm');
                    },
                    'format'    => 'raw',
                ],

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]);
    ?>

    <?php Pjax::end(); ?>
</div>
