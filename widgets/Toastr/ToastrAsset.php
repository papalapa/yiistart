<?php
    namespace vendor\papalapa\yii2\toastr;

    use yii\web\AssetBundle;

    /**
     * Class ToastrAsset
     * @package vendor\papalapa\yii2\toastr
     */
    class ToastrAsset extends AssetBundle
    {
        public $sourcePath     = '@vendor/codeseven/toastr/build';
        public $css            = [
            'toastr.min.css',
        ];
        public $js             = [
            'toastr.min.js',
        ];
        public $publishOptions = [
            'only' => [
                'toastr.min.css',
                'toastr.min.js',
            ],
        ];
    }
