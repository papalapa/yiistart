<?php

    namespace papalapa\yiistart\assets;

    use yii\web\AssetBundle;

    /**
     * Class CDN_Desandro_Masonry_Asset
     * @link    https://github.com/desandro/masonry
     * @link    https://cdnjs.com/libraries/masonry
     * @package common\assets
     */
    class CDN_Desandro_Masonry_Asset extends AssetBundle
    {
        public $js = [
            'https://cdnjs.cloudflare.com/ajax/libs/masonry/4.1.1/masonry.pkgd.min.js',
        ];
        public $depends = [
            'papalapa\yiistart\assets\CDN_Desandro_ImagesLoaded_Asset',
        ];
    }
