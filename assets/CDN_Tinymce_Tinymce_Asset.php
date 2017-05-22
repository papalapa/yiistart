<?php

    namespace papalapa\yiistart\assets;

    use yii\web\AssetBundle;

    /**
     * Class CDN_Tinymce_Tinymce_Asset
     * @link    https://github.com/tinymce/tinymce
     * @link    https://cdnjs.com/libraries/tinymce
     * @package common\assets
     */
    class CDN_Tinymce_Tinymce_Asset extends AssetBundle
    {
        public $js = [
            'https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.5.1/tinymce.min.js',
        ];
    }
