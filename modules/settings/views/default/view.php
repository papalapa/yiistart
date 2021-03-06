<?php

    use papalapa\yiistart\modules\settings\models\Settings;
    use papalapa\yiistart\modules\users\models\BaseUser;
    use papalapa\yiistart\widgets\ControlButtonsPanel;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model papalapa\yiistart\modules\settings\models\Settings */

    $this->title                   = $model->key;
    $this->params['breadcrumbs'][] = ['label' => 'Настройки', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="settings-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
        echo ControlButtonsPanel::widget([
            'items' => [
                'updateSetting' => [
                    'title' => 'Изменить',
                    'url'   => ['update', 'id' => $model->id],
                    'ico'   => 'fa fa-pencil',
                    'class' => 'btn btn-success',
                ],
                'deleteSetting' => [
                    'title' => 'Удалить',
                    'url'   => ['delete', 'id' => $model->id],
                    'ico'   => 'fa fa-trash',
                    'class' => 'btn btn-danger',
                    'data'  => [
                        'confirm' => 'Вы уверены, что хотите удалить?',
                        'method'  => 'post',
                    ],
                ],
            ],
        ]);

        $widgetClass = $model->multilingual ? 'papalapa\yiistart\widgets\MultilingualDetailView' : 'yii\widgets\DetailView';

        echo $widgetClass::widget([
            'model'      => $model,
            'attributes' => [
                // 'id',
                'title',
                'key',
                'value:ntext',
                [
                    'attribute' => 'type',
                    'value'     => ArrayHelper::getValue(Settings::types(), $model->type),
                ],
                'pattern',
                'comment',
                [
                    'attribute' => 'multilingual',
                    'value'     => Html::tag('i', null,
                        ['class' => $model->multilingual ? 'fa fa-check text-success' : 'fa fa-times-circle text-danger']),
                    'format'    => 'html',
                ],
                [
                    'attribute' => 'is_visible',
                    'value'     => Html::tag('i', null,
                        ['class' => $model->is_active ? 'fa fa-check text-success' : 'fa fa-times-circle text-danger']),
                    'format'    => 'html',
                ],
                [
                    'attribute' => 'is_active',
                    'value'     => Html::tag('i', null,
                        ['class' => $model->is_active ? 'fa fa-check text-success' : 'fa fa-times-circle text-danger']),
                    'format'    => 'html',
                ],
                [
                    'attribute' => 'created_by',
                    'value'     => ($model->created_by && $user = BaseUser::findOne(['id' => $model->created_by])) ? $user->email : null,
                    'format'    => 'email',
                ],
                [
                    'attribute' => 'updated_by',
                    'value'     => ($model->updated_by && $user = BaseUser::findOne(['id' => $model->updated_by])) ? $user->email : null,
                    'format'    => 'email',
                ],
                [
                    'attribute' => 'created_at',
                    'value'     => Yii::$app->formatter->asDate($model->created_at, 'd MMMM YYYY, HH:mm'),
                    'format'    => 'html',
                ],
                [
                    'attribute' => 'updated_at',
                    'value'     => Yii::$app->formatter->asDate($model->updated_at, 'd MMMM YYYY, HH:mm'),
                    'format'    => 'html',
                ],
            ],
        ]) ?>

</div>
