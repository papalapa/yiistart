<?php

    namespace papalapa\yiistart\assets;

    use yii\web\AssetBundle;

    class AppAsset extends AssetBundle
    {
        public $sourcePath = '@vendor/papalapa/yiistart/assets/app';
        public $css        = [
            'css/styles.css',
        ];
        public $js         = [
            'js/domready.js',
        ];
        public $depends    = [
            'yii\web\YiiAsset',
            'yii\bootstrap\BootstrapAsset',
            'papalapa\yiistart\assets\Fortawesome_FontAwesome_Asset',
            'papalapa\yiistart\assets\CDN_CodeSeven_Toastr_Asset',
            'papalapa\yiistart\assets\CDN_Flesler_JqueryScrollTo_Asset',
            'papalapa\yiistart\assets\CDN_Mimo84_BootstrapMaxlength_Asset',
            'papalapa\yiistart\assets\CDN_Zenorocha_ClipboardJs_Asset',
            'papalapa\yiistart\assets\CDN_Hakimel_LaddaButtons_Asset',
        ];
    }
