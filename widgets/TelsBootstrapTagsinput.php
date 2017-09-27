<?php

    namespace papalapa\yiistart\widgets;

    use papalapa\yiistart\assets\CDN_CodeSeven_Toastr_Asset;
    use papalapa\yiistart\validators\TelephoneValidator;
    use yii\helpers\Html;

    /**
     * Class BootstrapTagsinput
     * @package papalapa\yiistart\widgets
     */
    class TelsBootstrapTagsinput extends BootstrapTagsinput
    {
        public $pattern = '/^(\+?\d+( ?\(\d+\))?|\(\+?\d+\)) ?(\d+(-| )?)*\d+$/';
        public $errorTitle = 'Ошибка';
        public $errorText  = 'Неверно указан номер телефона';

        /**
         * @inheritdoc
         */
        public function init()
        {
            $this->pattern = (new TelephoneValidator())->pattern;
            parent::init();
        }

        /**
         * Registering client scripts
         */
        public function registerClientScripts()
        {
            parent::registerClientScripts();

            CDN_CodeSeven_Toastr_Asset::register($this->view);

            $this->view->registerJs(sprintf("
            $('#%s').on('beforeItemAdd', function(event){
                if (false === %s.test(event.item)){
                    toastr.error('%s', '%s');
                    event.cancel = true;
                }
            });", Html::getInputId($this->model, $this->attribute), $this->pattern, $this->errorText, $this->errorTitle));
        }
    }
