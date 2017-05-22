<?php

    namespace papalapa\yiistart\assets;

    use yii\web\AssetBundle;

    /**
     * Class CDN_Eonasdan_BootstrapDatetimepicker_Asset
     * @link    https://github.com/Eonasdan/bootstrap-datetimepicker
     * @link    https://cdnjs.com/libraries/bootstrap-datetimepicker
     * @package common\assets
     */
    class CDN_Eonasdan_BootstrapDatetimepicker_Asset extends AssetBundle
    {
        public $css     = [
            'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.43/css/bootstrap-datetimepicker.min.css',
        ];
        public $js      = [
            'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.43/js/bootstrap-datetimepicker.min.js',
        ];
        public $depends = [
            'papalapa\yiistart\assets\CDN_Moment_Moment_Asset',
        ];
    }
