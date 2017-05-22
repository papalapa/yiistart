<?php

    namespace papalapa\yiistart\assets;

    use yii\web\AssetBundle;

    /**
     * Class CDN_Gwatts_JquerySparkline_Asset
     * @link    https://github.com/gwatts/jquery.sparkline
     * @link    https://cdnjs.com/libraries/jquery-sparklines
     * @package common\assets
     */
    class CDN_Gwatts_JquerySparkline_Asset extends AssetBundle
    {
        public $js = [
            'https://cdnjs.cloudflare.com/ajax/libs/jquery-sparklines/2.1.2/jquery.sparkline.min.js',
        ];
    }
