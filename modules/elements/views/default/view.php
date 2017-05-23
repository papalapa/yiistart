<?php

    use papalapa\yiistart\modules\elements\models\Elements;
    use papalapa\yiistart\modules\users\models\BaseUser;
    use papalapa\yiistart\widgets\ControlButtonsPanel;
    use papalapa\yiistart\widgets\MultilingualDetailView;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model papalapa\yiistart\modules\elements\models\Elements */

    $this->title                   = $model->name;
    $this->params['breadcrumbs'][] = ['label' => 'Элементы', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="elements-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?
        echo ControlButtonsPanel::widget([
            'items' => [
                'updateElement' => [
                    'title' => 'Изменить',
                    'url'   => ['update', 'id' => $model->id],
                    'ico'   => 'fa fa-pencil',
                    'class' => 'btn btn-success',
                ],
                'deleteElement' => [
                    'title' => 'Удалить',
                    'url'   => ['delete', 'id' => $model->id],
                    'class' => 'btn btn-danger',
                    'ico'   => 'fa fa-trash',
                    'data'  => [
                        'confirm' => 'Вы уверены, что хотите удалить?',
                        'method'  => 'post',
                    ],
                ],
            ],
        ]);
    ?>

    <?
        echo MultilingualDetailView::widget([
            'model'      => $model,
            'attributes' => [
                'id',
                [
                    'attribute' => 'category_id',
                    'value'     => $model->category->name,
                ],
                'alias',
                'name',
                'text:html',
                [
                    'attribute' => 'format',
                    'value'     => ArrayHelper::getValue(Elements::formats(), $model->format),
                ],
                'description:text',
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
