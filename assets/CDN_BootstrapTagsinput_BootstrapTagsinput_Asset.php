<?php

    namespace papalapa\yiistart\assets;

    use yii\web\AssetBundle;

    /**
     * Class CDN_BootstrapTagsinput_BootstrapTagsinput_Asset
     * @link https://github.com/bootstrap-tagsinput/bootstrap-tagsinput
     * @link https://cdnjs.com/libraries/bootstrap-tagsinput
     * @package papalapa\yiistart\assets
     */
    class CDN_BootstrapTagsinput_BootstrapTagsinput_Asset extends AssetBundle
    {
        public $css = [
            'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css',
        ];
        public $js  = [
            'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js',
            //'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js.map',
        ];
    }
