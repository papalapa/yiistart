<?php

    use kartik\select2\Select2;
    use papalapa\yiistart\modules\users\models\BaseUser;
    use papalapa\yiistart\widgets\BootstrapActiveForm;
    use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $model \papalapa\yiistart\modules\users\models\User */
    /* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = BootstrapActiveForm::begin(); ?>

    <?= $form->field($model, 'email')->textInput() ?>

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
        $roles = BaseUser::roleDescription();
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
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Изменить',
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
        ) ?>
    </div>

    <?php BootstrapActiveForm::end(); ?>

</div>
