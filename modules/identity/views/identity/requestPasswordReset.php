<?php

    /* @var $this yii\web\View */
    /* @var $form yii\bootstrap\ActiveForm */
    /* @var $model RequestPasswordResetForm */

    use backend\modules\user\models\RequestPasswordResetForm;
    use yii\bootstrap\ActiveForm;
    use yii\helpers\Html;

    $this->title                   = 'Восстановление пароля';
    $this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-identity-request-reset">
    <div class="row">
        <div class="col-lg-4 col-lg-offset-4 col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
            <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

            <p>Укажите ваш адрес электронной почты. Мы отправим вам ссылку для смены пароля.</p>

            <?php $form = ActiveForm::begin(['id' => 'request-reset-form']); ?>

            <?= $form->field($model, 'email') ?>

            <div class="form-group text-center">
                <?= Html::submitButton('<i class="fa fa-envelope"></i> Отправить', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
