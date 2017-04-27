<?php

    namespace papalapa\yiistart\assets;

    use yii\web\AssetBundle;

    /**
     * Class CDN_Artpolikarpov_Fotorama_Asset
     * @link    https://github.com/artpolikarpov/fotorama/
     * @link    https://cdnjs.com/libraries/fotorama
     * @package papalapa\yiistart\assets
     */
    class CDN_Artpolikarpov_Fotorama_Asset extends AssetBundle
    {
        public $css = [
            'https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.min.css',
        ];
        public $js  = [
            'https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.min.js',
        ];
    }
