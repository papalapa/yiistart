<?php

    /* @var $this yii\web\View */
    /* @var $name string */
    /* @var $message string */
    /* @var $exception Exception */

    use yii\helpers\Html;

    $this->title = $name;

    if (Yii::$app->user->isGuest) {
        $this->context->layout = '@papalapa/yiistart/widgets/views/empty';
    }

?>
<div class="site-error">

    <div class="jumbotron">
        <h1><?= Html::encode($this->title) ?></h1>
        <p><?= nl2br(Html::encode($message)) ?></p>
    </div>

</div>
