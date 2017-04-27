<?php

    namespace papalapa\yiistart\assets;

    use yii\web\AssetBundle;

    /**
     * Class CDN_Dangrossman_BootstrapDaterangepicker_Asset
     * @link    https://github.com/dangrossman/bootstrap-daterangepicker
     * @link    https://cdnjs.com/libraries/bootstrap-daterangepicker
     * @package common\assets
     */
    class CDN_DangrossCman_BootstrapDaterangepicker_Asset extends AssetBundle
    {
        public $css = [
            'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.24/daterangepicker.min.css',
        ];
        public $js  = [
            'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.24/moment.min.js',
            'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.24/daterangepicker.min.js',
        ];
    }
