<?php
    /* @var $this yii\web\View */
    /* @var $model User */

    use common\models\User;
    use yii\bootstrap\ActiveForm;
    use yii\helpers\Html;

    $this->title                   = 'Смена пароля';
    $this->params['breadcrumbs'][] = $this->title;

?>
<h1><?= Html::encode($this->title) ?></h1>

<br />

<div class="profile-form">
    <?php
        $form = ActiveForm::begin([
            'id'          => 'profile-secure-form',
            'layout'      => 'horizontal',
            'fieldConfig' => [
                'horizontalCssClasses' => [
                    'wrapper' => 'col-lg-8 col-md-8',
                    'label'   => 'col-lg-4 col-md-4',
                    'offset'  => 'col-lg-offset-4 col-md-offset-4',
                    'hint'    => 'col-lg-8 col-lg-offset-4 col-md-8 col-md-offset-4',
                ],
            ],
        ]); ?>

    <?= $form->field($model, 'password')->passwordInput() ?>
    <?= $form->field($model, 'new_password')->passwordInput() ?>
    <?= $form->field($model, 'new_password_repeat')->passwordInput() ?>

    <div class="form-group">
        <div class="col-lg-8 col-lg-offset-4">
            <?= Html::submitButton(Html::tag('i', null, ['class' => 'fa fa-check']) . ' Сохранить', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
