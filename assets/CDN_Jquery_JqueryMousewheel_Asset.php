<?php

    namespace papalapa\yiistart\assets;

    use yii\web\AssetBundle;

    /**
     * Class CDN_Jquery_JqueryMousewheel_Asset
     * @link    https://github.com/jquery/jquery-mousewheel
     * @link    https://cdnjs.com/libraries/jquery-mousewheel
     * @package common\assets
     */
    class CDN_Jquery_JqueryMousewheel_Asset extends AssetBundle
    {
        public $js = [
            'https://cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.1.13/jquery.mousewheel.min.js',
        ];
    }
