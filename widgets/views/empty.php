<?php

    /* @var $this \yii\web\View */
    /* @var $content string */

    use papalapa\yiistart\assets\AppAsset;
    use papalapa\yiistart\widgets\Alert;
    use yii\bootstrap\BootstrapAsset;
    use yii\helpers\Html;

    AppAsset::register($this);
    BootstrapAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body style="background-color: #eeeeee;">
    <?php $this->beginBody() ?>

    <div class="wrap">
        <div class="container">
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </div>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
