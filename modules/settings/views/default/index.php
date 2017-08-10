<?php

    use papalapa\yiistart\modules\i18n\models\i18n;
    use papalapa\yiistart\modules\settings\models\Settings;
    use papalapa\yiistart\modules\users\models\BaseUser;
    use papalapa\yiistart\widgets\ControlButtonsPanel;
    use papalapa\yiistart\widgets\GridActionColumn;
    use papalapa\yiistart\widgets\GridTextColumn;
    use papalapa\yiistart\widgets\GridToggleColumn;
    use yii\grid\GridView;
    use yii\helpers\ArrayHelper;
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

    <?php Pjax::begin(['id' => 'pjax-settings-index', 'options' => ['class' => 'pjax-spinner table-responsive'], 'timeout' => 10000]); ?>

    <?
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns'      => [
                // ['class' => 'yii\grid\SerialColumn'],
                // 'id',
                [
                    'attribute' => 'key',
                    'visible'   => Yii::$app->user->identity->role == BaseUser::ROLE_DEVELOPER,
                ],
                [
                    'attribute' => 'title',
                    'content'   => function ($model) /* @var $model Settings */ {
                        return $model->title ? : Html::tag('i', $model->key, ['class' => 'text-danger']);
                    },
                ],
                [
                    'attribute' => 'value',
                    'class'     => GridTextColumn::className(),
                ],
                [
                    'attribute' => 'type',
                    'filter'    => Settings::types(),
                    'content'   => function ($model) /* @var $model Settings */ {
                        return ArrayHelper::getValue(Settings::types(), $model->type);
                    },
                ],
                [
                    'class'      => GridToggleColumn::className(),
                    'attribute'  => 'is_active',
                    'labelTitle' => 'Активность',
                    'labelIco'   => 'fa fa-eye',
                ],
                [
                    'class'      => GridToggleColumn::className(),
                    'attribute'  => 'is_visible',
                    'labelTitle' => 'Видимость',
                    'labelIco'   => 'fa fa-check-square',
                    'visible'    => Yii::$app->user->identity->role == BaseUser::ROLE_DEVELOPER,
                ],
                [
                    'class'      => GridToggleColumn::className(),
                    'attribute'  => 'multilingual',
                    'labelTitle' => 'Мультиязычность',
                    'labelIco'   => 'fa fa-language',
                    'visible'    => Yii::$app->user->identity->role == BaseUser::ROLE_DEVELOPER && count(i18n::locales()) > 1,
                ],
                /*[
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
                ],*/
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
