<?php
    namespace papalapa\yiistart\widgets;

    use papalapa\yiistart\assets\ToastrAsset;
    use yii;
    use yii\base\Widget;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\helpers\Json;

    /**
     * Class Toastr
     * Use toastr to display short messages in app
     * @link    http://codeseven.github.io/toastr/
     * @package papalapa\yiistart\widgets
     */
    class Toastr extends Widget
    {
        /**
         * Toastr types
         */
        const TYPE_ERROR   = 'error';
        const TYPE_INFO    = 'info';
        const TYPE_SUCCESS = 'success';
        const TYPE_WARNING = 'warning';
        /**
         * Toastr type
         * @var string
         */
        public $type = self::TYPE_INFO;
        /**
         * Toastr title
         * @var string
         */
        public $title;
        /**
         * Toastr text
         * @var string
         */
        public $message;
        /**
         * ToastrAsset is required in global asset by default
         * If you need to register it in ajax request, set to true
         * @var bool
         */
        public $isAjax = false;
        /**
         * Client options to merge with plugin options
         * @var array
         */
        public $clientOptions = [];
        /**
         * Plugin options
         * @var array
         */
        private $pluginOptions = [
            'closeButton'       => true,
            'debug'             => false,
            'newestOnTop'       => true,
            'progressBar'       => false,
            'preventDuplicates' => false,
            'onclick'           => null,
            'positionClass'     => 'toast-top-right',
            'showDuration'      => '250',
            'hideDuration'      => '1000',
            'timeOut'           => '5000',
            'extendedTimeOut'   => '1000',
            'showEasing'        => 'swing',
            'hideEasing'        => 'linear',
            'showMethod'        => 'fadeIn',
            'hideMethod'        => 'fadeOut',
        ];

        public function run()
        {
            $options = Json::htmlEncode(ArrayHelper::merge($this->pluginOptions, (array)$this->clientOptions));
            $js      = "toastr.{$this->type}('{$this->message}', '{$this->title}', {$options});";

            if ($this->isAjax) {
                /* If App global assets not depends of Toastr, uncomment this rows */
                // $view   = new View();
                // $asset  = ToastrAsset::register($view);
                // $script = Yii::$app->assetManager->getAssetUrl($asset, 'toastr.min.js');

                return implode(PHP_EOL, [
                    /* And this one */
                    // Html::jsFile($script),
                    Html::script($js),
                ]);
            }

            ToastrAsset::register($this->view);
            $this->view->registerJs($js);

            return null;
        }
    }
