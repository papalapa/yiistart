<?php

    use papalapa\yiistart\modules\users\models\BaseUser;
    use papalapa\yiistart\widgets\ControlButtonsPanel;
    use yii\grid\GridView;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\widgets\Pjax;

    /* @var $this yii\web\View */
    /* @var $searchModel backend\modules\user\models\UserSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */

    $this->title                   = 'Пользователи';
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?
        echo ControlButtonsPanel::widget([
            'items' => [
                'createUser' => [
                    'title' => 'Создать пользователя',
                    'url'   => ['create'],
                    'ico'   => 'fa fa-plus-circle',
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
                //['class' => 'yii\grid\SerialColumn'],

                'id',
                'email',
                //'auth_key',
                //'password_hash',
                //'token',
                [
                    'attribute' => 'status',
                    'filter'    => BaseUser::statusDescription(),
                    'content'   => function ($model, $key, $index, $column) {
                        switch ($model->status) {
                            case BaseUser::STATUS_ACTIVE:
                                $style = 'text-success';
                                break;
                            case BaseUser::STATUS_READY:
                                $style = 'text-info';
                                break;
                            case BaseUser::STATUS_DELETED:
                                $style = 'text-danger';
                                break;
                            default:
                                $style = null;
                                break;
                        }

                        /**
                         * @var \papalapa\yiistart\modules\users\models\User $model
                         */
                        return Html::tag('span', ArrayHelper::getValue(BaseUser::statusDescription(), $model->status), ['class' => $style]);
                    },
                ],
                [
                    'attribute' => 'role',
                    'filter'    => BaseUser::roleDescription(),
                    'content'   => function ($model, $key, $index, $column) {
                        /**
                         * @var \papalapa\yiistart\modules\users\models\User $model
                         */
                        return ArrayHelper::getValue(BaseUser::roleDescription(), $model->role);
                    },
                ],
                // 'created_at',
                // 'updated_at',
                // 'activity_at',
                // 'last_ip',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]);
    ?>

    <?php Pjax::end(); ?>
</div>
