<?php

    namespace papalapa\yiistart\assets;

    use yii\web\AssetBundle;

    /**
     * Class CDN_Weareoutman_Clockpicker_Asset
     * @link    https://github.com/weareoutman/clockpicker
     * @link    https://cdnjs.com/libraries/clockpicker
     * @package common\assets
     */
    class CDN_Weareoutman_Clockpicker_Asset extends AssetBundle
    {
        public $css = [
            'https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/bootstrap-clockpicker.css',
        ];
        public $js  = [
            'https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/bootstrap-clockpicker.min.js',
        ];
    }
