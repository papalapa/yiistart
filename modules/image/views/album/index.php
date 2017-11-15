<?php

    use papalapa\yiistart\widgets\ControlButtonsPanel;
    use papalapa\yiistart\widgets\GridActionColumn;
    use papalapa\yiistart\widgets\GridToggleColumn;
    use yii\grid\GridView;
    use yii\helpers\Html;
    use yii\widgets\Pjax;

    /* @var $this yii\web\View */
    /* @var $searchModel papalapa\yiistart\modules\image\models\AlbumSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */

    $this->title                   = 'Альбомы';
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="album-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= ControlButtonsPanel::widget([
        'items' => [
            'createAlbum' => [
                'title' => 'Создать',
                'url'   => ['create'],
                'ico'   => 'fa fa-plus-circle',
                'class' => 'btn btn-success',
            ],
            'indexImage'  => [
                'title' => 'Изображения',
                'url'   => ['/image'],
                'ico'   => 'fa fa-th-large',
                'class' => 'btn btn-default',
            ],
        ],
    ]); ?>

    <?php Pjax::begin(['id' => 'pjax-album-index', 'options' => ['class' => 'pjax-spinner table-responsive'], 'timeout' => 10000]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            //['class' => 'yii\grid\SerialColumn'],

            'id',
            'alias',
            'name',
            // 'scale',
            // 'template',
            [
                'attribute'  => 'has_name',
                'class'      => GridToggleColumn::className(),
                'labelTitle' => 'Заголовок',
                'labelIco'   => 'fa fa-header',
            ],
            [
                'attribute'  => 'has_alt',
                'class'      => GridToggleColumn::className(),
                'labelTitle' => 'ALT',
                'labelIco'   => 'fa fa-font',
            ],
            [
                'attribute'  => 'has_title',
                'class'      => GridToggleColumn::className(),
                'labelTitle' => 'TITLE',
                'labelIco'   => 'fa fa-text-width',
            ],
            [
                'attribute'  => 'has_text',
                'class'      => GridToggleColumn::className(),
                'labelTitle' => 'Текст',
                'labelIco'   => 'fa fa-file-text-o',
            ],
            [
                'attribute'  => 'has_caption',
                'class'      => GridToggleColumn::className(),
                'labelTitle' => 'Описание',
                'labelIco'   => 'fa fa-align-center',
            ],
            [
                'attribute'  => 'has_src',
                'class'      => GridToggleColumn::className(),
                'labelTitle' => 'Изображение',
                'labelIco'   => 'fa fa-image',
            ],
            [
                'attribute'  => 'has_cssclass',
                'class'      => GridToggleColumn::className(),
                'labelTitle' => 'CSS класс',
                'labelIco'   => 'fa fa-css3',
            ],
            [
                'attribute'  => 'has_twin',
                'class'      => GridToggleColumn::className(),
                'labelTitle' => 'Близнец',
                'labelIco'   => 'fa fa-copy',
            ],
            // 'has_twin_cssclass',
            [
                'attribute'  => 'has_link',
                'class'      => GridToggleColumn::className(),
                'labelTitle' => 'Ссылка',
                'labelIco'   => 'fa fa-link',
            ],
            // 'has_link_cssclass',
            // 'validator_controller',
            // 'validator_extensions',
            // 'validator_min_size',
            // 'validator_max_size',
            // 'description:ntext',
            [
                'attribute'  => 'is_multilingual_images',
                'class'      => GridToggleColumn::className(),
                'labelTitle' => 'Мультиязычные изображения',
                'labelIco'   => 'fa fa-language',
            ],
            [
                'attribute'  => 'is_visible',
                'class'      => GridToggleColumn::className(),
                'labelTitle' => 'Видимость',
                'labelIco'   => 'fa fa-low-vision',
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
                    'view'   => 'viewAlbum',
                    'update' => 'updateAlbum',
                    'delete' => 'deleteAlbum',
                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>
