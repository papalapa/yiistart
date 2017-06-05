<?php

    namespace papalapa\yiistart\assets;

    use yii\web\AssetBundle;

    /**
     * Class CDN_CodeSeven_Toastr_Asset
     * @link    https://cdnjs.com/libraries/toastr.js
     * @package papalapa\yiistart\assets
     */
    class CDN_CodeSeven_Toastr_Asset extends AssetBundle
    {
        public $css     = [
            'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.3/toastr.min.css',
        ];
        public $js      = [
            'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.3/toastr.min.js',
        ];
        public $depends = [
            'yii\web\JqueryAsset',
        ];
    }
