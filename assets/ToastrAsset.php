<?php
    namespace vendor\papalapa\yii2start\assets;

    use yii\web\AssetBundle;

    /**
     * Class ToastrAsset
     * @link https://cdnjs.com/libraries/toastr.js
     * @package vendor\papalapa\yii2start\assets
     */
    class ToastrAsset extends AssetBundle
    {
        public $css = [
            'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.3/toastr.min.css',
        ];
        public $js  = [
            'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.3/toastr.min.js',
            'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.3/toastr.js.map',
        ];
    }
