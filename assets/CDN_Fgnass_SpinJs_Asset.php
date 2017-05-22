<?php

    namespace papalapa\yiistart\assets;

    use yii\web\AssetBundle;

    /**
     * Class CDN_Fgnass_SpinJs_Asset
     * @link    https://github.com/fgnass/spin.js
     * @link    https://cdnjs.com/libraries/spin.js
     * @package common\assets
     */
    class CDN_Fgnass_SpinJs_Asset extends AssetBundle
    {
        public $js = [
            'https://cdnjs.cloudflare.com/ajax/libs/spin.js/2.3.2/spin.min.js',
            'http://spin.js.org/jquery.spin.js',
        ];
    }
