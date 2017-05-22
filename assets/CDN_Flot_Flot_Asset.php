<?php

    namespace papalapa\yiistart\assets;

    use yii\web\AssetBundle;

    /**
     * Class CDN_Flot_Flot_Asset
     * @link    https://github.com/flot/flot
     * @link    https://cdnjs.com/libraries/flot
     * @package common\assets
     */
    class CDN_Flot_Flot_Asset extends AssetBundle
    {
        public $js      = [
            'https://cdnjs.cloudflare.com/ajax/libs/flot/0.8.3/jquery.flot.min.js',
            'https://cdnjs.cloudflare.com/ajax/libs/flot/0.8.3/jquery.flot.time.min.js',
            'https://cdnjs.cloudflare.com/ajax/libs/flot/0.8.3/jquery.flot.resize.min.js',
            'https://cdnjs.cloudflare.com/ajax/libs/flot/0.8.3/jquery.flot.threshold.min.js',
        ];
        public $depends = [
            'papalapa\yiistart\assets\Arv_Explorercanvas_Asset',
        ];
    }
