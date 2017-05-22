<?php

    namespace papalapa\yiistart\assets;

    use yii\web\AssetBundle;

    /**
     * Class YandexYandex_ShareAsset
     * @package papalapa\yiistart\assets
     */
    class YandexYandex_ShareAsset extends AssetBundle
    {
        public $js        = [
            '//yastatic.net/es5-shims/0.0.2/es5-shims.min.js',
            '//yastatic.net/share2/share.js',
        ];
        public $jsOptions = [
            'async' => 'async',
        ];
    }
