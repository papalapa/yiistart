<?php

    use papalapa\yiistart\models\User;
    use papalapa\yiistart\modules\pages\models\Pages;
    use papalapa\yiistart\widgets\ControlButtonsPanel;
    use papalapa\yiistart\widgets\MultilingualDetailView;
    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model \papalapa\yiistart\modules\pages\models\Pages */

    $this->title                   = $model->title;
    $this->params['breadcrumbs'][] = ['label' => 'Страницы', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="pages-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?
        echo ControlButtonsPanel::widget([
            'items' => [
                'updatePage' => [
                    'title' => 'Изменить',
                    'url'   => ['update', 'id' => $model->id],
                    'ico'   => 'fa fa-pencil',
                    'class' => 'btn btn-success',
                ],
                'deletePage' => [
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

        $siteUrlManager          = clone (Yii::$app->urlManager);
        $siteUrlManager->baseUrl = '/';

        echo MultilingualDetailView::widget([
            'model'      => $model,
            'attributes' => [
                [
                    'attribute' => 'id',
                    'format'    => 'html',
                    'value'     => function ($model) use ($siteUrlManager) {
                        /**
                         * @var Pages $model
                         */
                        return Html::a(
                            $siteUrlManager->createAbsoluteUrl(['/site/page', 'id' => $model->id]),
                            $siteUrlManager->createUrl(['/site/page', 'id' => $model->id]),
                            ['target' => '_blank']
                        );
                    },
                ],
                [
                    'attribute' => 'header',
                    'format'    => 'html',
                    'value'     => function ($model) /* @var Pages $model */ {
                        return Html::tag('h4', $model->header);
                    },
                ],
                [
                    'attribute' => 'image',
                    'format'    => 'html',
                    'value'     => function ($model) /* @var Pages $model */ {
                        return $model->image ? Html::img($model->image, ['width' => 200]) : null;
                    },
                ],
                'context:html',
                'text:html',
                'title',
                'description',
                'keywords',
                [
                    'attribute' => 'is_active',
                    'value'     => Html::tag('i', null,
                        ['class' => $model->is_active ? 'fa fa-check text-success' : 'fa fa-times-circle text-danger']),
                    'format'    => 'html',
                ],
                [
                    'attribute' => 'created_by',
                    'value'     => $model->created_by ? User::findOne(['id' => $model->created_by])->email : null,
                    'format'    => 'email',
                ],
                [
                    'attribute' => 'updated_by',
                    'value'     => $model->updated_by ? User::findOne(['id' => $model->updated_by])->email : null,
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
        ]);
    ?>

</div>
