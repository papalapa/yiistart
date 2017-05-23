<?php

    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model \common\modules\user\models\User */

    $this->title                   = 'Создание пользователя';
    $this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_signup_form', [
        'model' => $model,
    ]) ?>

</div>
