<?php

    /* @var $this yii\web\View */
    /* @var $form yii\bootstrap\ActiveForm */
    /* @var $model LoginForm */

    use backend\modules\user\models\LoginForm;
    use yii\bootstrap\ActiveForm;
    use yii\helpers\Html;

    $this->title                   = 'Авторизация';
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-identity-login">
    <div class="row">
        <div class="col-lg-4 col-lg-offset-4 col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
            <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

            <br />

            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

            <?= $form->field($model, 'email') ?>
            <?= $form->field($model, 'password')->passwordInput() ?>

            <p class="text-center">
                <?= $form->field($model, 'rememberMe')->checkbox(['id' => 'login-form-remember']) ?>
            </p>

            <div class="form-group text-center">
                <?= Html::submitButton('<i class="fa fa-sign-in"></i> Попытаться войти',
                    ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>

            <p class="text-center">
                <?= Html::a('Восстановить пароль', ['identity/request-password-reset']) ?>
            </p>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
