<?php

    namespace papalapa\yiistart\assets;

    use yii\web\AssetBundle;
    use yii\web\View;

    /**
     * Class IEAsset
     * @package papalapa\yiistart\assets
     */
    class IEAsset extends AssetBundle
    {
        public $js        = [
            'https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js',
            'https://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js',
        ];
        public $jsOptions = [
            'position'  => View::POS_HEAD,
            'condition' => 'lt IE9',
        ];
    }
