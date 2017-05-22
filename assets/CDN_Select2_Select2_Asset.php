<?php

    namespace papalapa\yiistart\assets;

    use yii\web\AssetBundle;

    /**
     * Class Select2_Select2Asset
     * @link    https://github.com/select2/select2
     * @link    https://cdnjs.com/libraries/select2
     * @package common\assets
     */
    class CDN_Select2_Select2_Asset extends AssetBundle
    {
        public $css = [
            'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css',
        ];
        public $js  = [
            'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js',
            'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/i18n/ru.js',
        ];
    }
