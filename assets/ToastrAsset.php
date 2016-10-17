<?php
    namespace vendor\papalapa\yii2start\assets;

    use yii\web\AssetBundle;

    /**
     * Class ToastrAsset
     * @package vendor\papalapa\yii2start\assets
     */
    class ToastrAsset extends AssetBundle
    {
        public $sourcePath     = '@vendor/papalapa/yii2-start/assets/js/toastr';
        public $css            = [
            'toastr.min.css',
        ];
        public $js             = [
            'toastr.js',
        ];
        public $publishOptions = [
            'only' => [
                'toastr.min.css',
                'toastr.min.js',
            ],
        ];
    }
