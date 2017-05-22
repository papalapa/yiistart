<?php

    namespace papalapa\yiistart\assets;

    use yii\web\AssetBundle;

    /**
     * Class Fortawesome_FontAwesome_Asset
     * @package papalapa\yiistart\assets
     */
    class Fortawesome_FontAwesome_Asset extends AssetBundle
    {
        public $sourcePath     = '@vendor/fortawesome/font-awesome';
        public $css            = [
            'css/font-awesome.min.css',
        ];
        public $publishOptions = [
            'only'   => [
                'fonts/*',
                'css/font-awesome.min.css',
            ],
            'except' => [
                'less',
                'scss',
                'src',
            ],
        ];
    }
