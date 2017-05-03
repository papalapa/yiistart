<?php

    namespace papalapa\yiistart\widgets;

    use papalapa\yiistart\assets\BootstrapActiveForm_Asset;
    use yii\bootstrap\ActiveForm;

    /**
     * Class BootstrapActiveForm
     * @see BootstrapActiveField
     * @package papalapa\yiistart\widgets
     */
    class BootstrapActiveForm extends ActiveForm
    {
        /**
         * @var string
         */
        public $fieldClass = 'papalapa\yiistart\widgets\BootstrapActiveField';
        /**
         * @var array
         */
        public $options = ['class' => 'bootstrap-active-form'];

        /**
         * Register asset to render radios and checkboxes
         */
        public function init()
        {
            BootstrapActiveForm_Asset::register($this->view);

            parent::init();
        }
    }
