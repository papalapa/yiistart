<?php

    use kartik\select2\Select2;
    use papalapa\yiistart\modules\users\models\BaseUser;
    use papalapa\yiistart\widgets\BootstrapActiveForm;
    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model \common\modules\user\models\User */
    /* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = BootstrapActiveForm::begin(); ?>

    <?= $form->field($model, 'email')->textInput(['readonly' => $model->isNewRecord ? false : 'readonly']) ?>

    <?
        $statuses = BaseUser::statusDescription();
        echo $form->field($model, 'status')->widget(Select2::className(), [
            'data'          => $statuses,
            'options'       => [
                'placeholder' => 'Выберите статус',
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ]);
    ?>

    <?
        $roles = [
            BaseUser::ROLE_USER    => 'Пользователь',
            BaseUser::ROLE_AUTHOR  => 'Автор',
            BaseUser::ROLE_MANAGER => 'Менеджер',
            BaseUser::ROLE_ADMIN   => 'Администратор',
        ];
        if (Yii::$app->user->identity->role === BaseUser::ROLE_DEVELOPER) {
            $roles[BaseUser::ROLE_DEVELOPER] = 'Разработчик';
        }
        echo $form->field($model, 'role')->widget(Select2::className(), [
            'data'          => $roles,
            'options'       => [
                'placeholder' => 'Выберите роль',
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ]);
    ?>

    <hr />

    <div class="form-group">
        <?= Html::submitButton(Html::tag('i', null, ['class' => 'fa fa-check']).' '.($model->isNewRecord
                ? 'Создать' : 'Изменить'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php BootstrapActiveForm::end(); ?>

</div>
