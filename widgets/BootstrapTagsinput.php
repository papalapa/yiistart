<?php

    namespace papalapa\yiistart\widgets;

    use papalapa\yiistart\assets\CDN_BootstrapTagsinput_BootstrapTagsinput_Asset;
    use yii\bootstrap\InputWidget;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\helpers\Json;

    /**
     * Class BootstrapTagsinput
     * @package papalapa\yiistart\widgets
     */
    class BootstrapTagsinput extends InputWidget
    {
        /**
         * @var array
         */
        public $pluginOptions = [
            'trimValue'       => true,
            'allowDuplicates' => false,
            'maxTags'         => 3,
            'maxChars'        => 16,
        ];
        /**
         * @var array
         */
        public $clientOptions = [];

        /**
         * @inheritdoc
         */
        public function init()
        {
            parent::init();

            $this->pluginOptions        = ArrayHelper::merge($this->pluginOptions, $this->clientOptions);
            $this->options['data-role'] = 'tagsinput';
        }

        /**
         * @return string
         */
        public function run()
        {
            $this->registerClientScripts();

            return implode(null, [
                Html::activeTextInput($this->model, $this->attribute, $this->options),
            ]);
        }

        /**
         * Registering client scripts
         */
        public function registerClientScripts()
        {
            CDN_BootstrapTagsinput_BootstrapTagsinput_Asset::register($this->view);

            $this->view->registerCss("
                .bootstrap-tagsinput {
                    width: 100%;
                    line-height: 25px;
                }
                .tag:after {
                    display: none;
                }
                .bootstrap-tagsinput .tag {
                    font-size: 100%;
                    margin-bottom: 0;
                    padding: 6px;
                }
            ");

            $this->view->registerJs(sprintf("$('#%s').tagsinput(%s);", Html::getInputId($this->model, $this->attribute), Json::encode($this->pluginOptions)));
        }
    }
