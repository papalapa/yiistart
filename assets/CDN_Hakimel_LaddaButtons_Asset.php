<?php

    namespace papalapa\yiistart\assets;

    use yii\web\AssetBundle;

    /**
     * Class CDN_Hakimel_LaddaButtons_Asset
     * @link    https://github.com/hakimel/Ladda
     * @link    https://cdnjs.com/libraries/Ladda
     * @package common\assets
     */
    class CDN_Hakimel_LaddaButtons_Asset extends AssetBundle
    {
        public $css     = [
            'https://cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.0/ladda-themeless.min.css',
        ];
        public $js      = [
            'https://cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.0/ladda.min.js',
            'https://cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.0/ladda.jquery.min.js',
        ];
        public $depends = [
            'papalapa\yiistart\assets\CDN_Fgnass_SpinJs_Asset',
        ];
    }
