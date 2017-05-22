<?php

    namespace papalapa\yiistart\assets;

    use yii\web\AssetBundle;

    /**
     * Class BootstrapActiveForm_Asset
     * @package papalapa\yiistart\assets
     */
    class BootstrapActiveForm_Asset extends AssetBundle
    {
        public $sourcePath     = '@vendor/papalapa/yiistart/assets/app/css/active-form';
        public $css            = [
            'styles.css',
        ];
        public $publishOptions = [
            'only' => [
                'styles.css',
            ],
        ];
    }
