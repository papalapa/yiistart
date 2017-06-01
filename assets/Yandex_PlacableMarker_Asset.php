<?php

    namespace papalapa\yiistart\assets;

    use yii\web\AssetBundle;

    /**
     * Class Yandex_PlacableMarker_Asset
     * @package papalapa\yiistart\assets
     */
    class Yandex_PlacableMarker_Asset extends AssetBundle
    {
        public $sourcePath = '@vendor/papalapa/yiistart/assets/app/js';
        public $js         = [
            // 'placeble-marker.js',
        ];
        public $depends    = [
            'papalapa\yiistart\assets\Yandex_YandexMap_Asset',
        ];
    }
