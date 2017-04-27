<?php

    namespace papalapa\yiistart\assets;

    use yii\web\AssetBundle;

    /**
     * Class CDN_Moment_Moment_Asset
     * @link    https://github.com/moment/moment
     * @link    https://cdnjs.com/libraries/moment.js
     * @package common\assets
     */
    class CDN_Moment_Moment_Asset extends AssetBundle
    {
        public $js = [
            'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment-with-locales.min.js',
        ];
    }
