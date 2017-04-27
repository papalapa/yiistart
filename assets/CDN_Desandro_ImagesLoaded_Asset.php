<?php

    namespace papalapa\yiistart\assets;

    use yii\web\AssetBundle;

    /**
     * Class CDN_Desandro_ImagesLoaded_Asset
     * @link    https://github.com/desandro/imagesloaded
     * @link    https://cdnjs.com/libraries/jquery.imagesloaded
     * @package common\assets
     */
    class CDN_Desandro_ImagesLoaded_Asset extends AssetBundle
    {
        public $js = [
            'https://cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/4.1.1/imagesloaded.pkgd.min.js',
        ];
    }
