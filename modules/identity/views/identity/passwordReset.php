<?php

    /* @var $this yii\web\View */
    /* @var $form yii\bootstrap\ActiveForm */
    /* @var $model PasswordResetForm */

    use backend\modules\user\models\PasswordResetForm;
    use yii\bootstrap\ActiveForm;
    use yii\helpers\Html;

    $this->title                   = 'Сброс пароля';
    $this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-identity-reset">
    <div class="row">
        <div class="col-lg-4 col-lg-offset-4 col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
            <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

            <p>Придумайте новый пароль.</p>

            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <div class="form-group text-center">
                <?= Html::submitButton('<i class="fa fa-lock"></i> Сохранить', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
