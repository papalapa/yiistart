<?php

    use backend\widgets\Permissions;
    use common\helpers\Filer;
    use common\models\Pages;
    use common\models\User;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\DetailView;

    /* @var $this yii\web\View */
    /* @var $model common\models\Pages */

    $this->title                   = $model->url;
    $this->params['breadcrumbs'][] = ['label' => 'Страницы', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="pages-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?
        echo Permissions::widget([
            'items' => [
                'updatePage' => [
                    'title' => 'Изменить',
                    'url'   => ['update', 'id' => $model->id],
                    'class' => 'btn btn-success',
                ],
                'deletePage' => [
                    'title' => 'Удалить',
                    'url'   => ['delete', 'id' => $model->id],
                    'class' => 'btn btn-danger',
                    'data'  => [
                        'confirm' => 'Вы уверены, что хотите удалить?',
                        'method'  => 'post',
                    ],
                ],
            ],
        ]);

        $siteUrlManager          = clone (Yii::$app->urlManager);
        $siteUrlManager->baseUrl = '/';

        echo DetailView::widget([
            'model'      => $model,
            'attributes' => [
                'id',
                [
                    'attribute' => 'url',
                    'format'    => 'html',
                    'value'     => function ($model) use ($siteUrlManager) {
                        /**
                         * @var Pages $model
                         */
                        return Html::tag('h4',
                            Html::a(
                                $siteUrlManager->createAbsoluteUrl(['/site/' . $model->url]),
                                $siteUrlManager->createUrl(['site/' . $model->url]),
                                ['target' => '_blank']
                            )
                        );
                    },
                ],
                [
                    'attribute' => 'header_ru',
                    'format'    => 'html',
                    'value'     => function ($model) {
                        /**
                         * @var Pages $model
                         */
                        return Html::tag('h4', $model->header_ru);
                    },
                ],
                [
                    'attribute' => 'header_en',
                    'format'    => 'html',
                    'value'     => function ($model) {
                        /**
                         * @var Pages $model
                         */
                        return Html::tag('h4', $model->header_en);
                    },
                ],
                [
                    'attribute' => 'img',
                    'format'    => 'html',
                    'value'     => function ($model) {
                        /**
                         * @var Pages $model
                         */
                        $img = $model->img ? Html::img($model->img, ['class' => 'img-responsive']) : null;

                        return $img ? Html::tag('div', $img, ['style' => 'width:auto;max-width:300px']) : null;
                    },
                ],
                'text_ru:html',
                'text_en:html',
                'title_en',
                'title_en',
                'description_ru',
                'description_en',
                'keywords_ru',
                'keywords_en',
                [
                    'attribute' => 'contextable',
                    'value'     => Html::tag('i', null,
                        ['class' => $model->contextable ? 'fa fa-check text-success' : 'fa fa-times-circle text-danger']),
                    'format'    => 'html',
                ],
                [
                    'attribute' => 'imagable',
                    'value'     => Html::tag('i', null,
                        ['class' => $model->imagable ? 'fa fa-check text-success' : 'fa fa-times-circle text-danger']),
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
