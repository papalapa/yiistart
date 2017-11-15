<?php

    use papalapa\yiistart\modules\image\models\Album;
    use papalapa\yiistart\modules\image\models\Image;
    use papalapa\yiistart\modules\users\models\BaseUser;
    use papalapa\yiistart\widgets\ControlButtonsPanel;
    use papalapa\yiistart\widgets\GridActionColumn;
    use papalapa\yiistart\widgets\GridImageColumn;
    use papalapa\yiistart\widgets\GridOrderColumn;
    use papalapa\yiistart\widgets\GridToggleColumn;
    use yii\grid\GridView;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\widgets\Pjax;

    /* @var $this yii\web\View */
    /* @var $searchModel papalapa\yiistart\modules\image\models\ImageSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */

    $this->title                   = 'Изображения';
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="image-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= ControlButtonsPanel::widget([
        'items' => [
            'createImage' => [
                'title' => 'Создать',
                'url'   => ['create'],
                'ico'   => 'fa fa-plus-circle',
                'class' => 'btn btn-success',
            ],
            'indexAlbum'  => [
                'title' => 'Альбомы',
                'url'   => ['album/index'],
                'ico'   => 'fa fa-th-large',
                'class' => 'btn btn-default',
            ],
        ],
    ]); ?>

    <?php Pjax::begin(['id' => 'pjax-image-index', 'options' => ['class' => 'pjax-spinner table-responsive'], 'timeout' => 10000]); ?>

    <?
        $albumsQuery = Album::find();
        if (Yii::$app->user->identity->role <> BaseUser::ROLE_DEVELOPER) {
            $albumsQuery->andWhere(['is_visible' => true]);
        }
        $albums = $albumsQuery->orderBy(['name' => SORT_ASC])->all();

        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns'      => [
                //['class' => 'yii\grid\SerialColumn'],

                'id',
                [
                    'attribute' => 'album_id',
                    'filter'    => ArrayHelper::map($albums, 'id', 'name'),
                    'content'   => function ($model) /* @var $model Image */ {
                        return $model->album ? $model->album->name : null;
                    },
                ],
                'name',
                'alt',
                'title',
                // 'text:ntext',
                // 'caption:ntext',
                [
                    'attribute' => 'src',
                    'class'     => GridImageColumn::className(),
                ],
                // 'cssclass',
                // 'twin',
                // 'twin_cssclass',
                // 'link',
                // 'link_cssclass',
                // 'size',
                // 'width',
                // 'height',
                [
                    'attribute' => 'order',
                    'class'     => GridOrderColumn::className(),
                ],
                [
                    'attribute'  => 'is_active',
                    'class'      => GridToggleColumn::className(),
                    'labelTitle' => 'Активность',
                    'labelIco'   => 'fa fa-eye',
                ],
                // 'created_by',
                // 'updated_by',
                // 'created_at',
                // 'updated_at',

                //['class' => 'yii\grid\ActionColumn'],
                [
                    'class'       => GridActionColumn::className(),
                    'permissions' => [
                        'view'   => 'viewImage',
                        'update' => 'updateImage',
                        'delete' => 'deleteImage',
                    ],
                ],
            ],
        ]); ?>

    <?php Pjax::end(); ?>
</div>
