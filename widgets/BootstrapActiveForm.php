<?php

    namespace vendor\papalapa\yii2\widgets;

    use yii\bootstrap\ActiveForm;

    /**
     * Class BootstrapActiveForm
     * @package vendor\papalapa\yii2\widgets
     */
    class BootstrapActiveForm extends ActiveForm
    {
        public $fieldClass = 'vendor\papalapa\yii2\widgets\BootstrapActiveField';
        public $options    = ['role' => 'form', 'class' => 'bootstrapForm'];
    }