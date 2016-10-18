<?php
    namespace papalapa\yii2start\widgets;

    use papalapa\yii2start\assets\ToastrAsset;
    use yii;
    use yii\base\Widget;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\helpers\Json;

    /**
     * Class Toastr
     * @package papalapa\yii2start\widgets
     */
    class Toastr extends Widget
    {
        const TYPE_ERROR   = 'error';
        const TYPE_INFO    = 'info';
        const TYPE_SUCCESS = 'success';
        const TYPE_WARNING = 'warning';
        public $type;
        public $title;
        public $message;
        /**
         * ToastrAsset is required in global asset by default
         * If you need to register it in ajax request, set to false
         * @var bool
         */
        public  $isAjax        = false;
        public  $clientOptions = [];
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

            if ($this->isAjax) {
                /* If App global assets not depend of Toastr, uncomment this rows */
                // $view   = new View();
                // $asset  = CodeSeven_ToastrAsset::register($view);
                // $script = Yii::$app->assetManager->getAssetUrl($asset, 'toastr.min.js');

                return implode(PHP_EOL, [
                    /* And this row */
                    // Html::jsFile($script),
                    Html::script("toastr.{$this->type}('{$this->message}', '{$this->title}', {$options});"),
                ]);
            }

            ToastrAsset::register($this->view);
            $this->view->registerJs("toastr.{$this->type}('{$this->message}', '{$this->title}', {$options});");

            return null;
        }
    }
