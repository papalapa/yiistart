<?php

    namespace papalapa\yiistart\widgets;

    use kartik\file\FileInput;
    use papalapa\yiistart\modules\settings\models\Settings;
    use yii\bootstrap\Html;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Url;

    /**
     * Class MultiImages
     * @package papalapa\yiistart\widgets
     */
    class MultiImages extends FileInput
    {
        /**
         * @var \yii\db\ActiveRecord
         */
        public $model;
        public $initialAttribute = '_images';
        /**
         * Input name
         * @var string
         */
        public $name    = 'image';
        public $options = [
            'accept'   => 'image/*',
            'multiple' => true,
        ];
        /**
         * Plugin options
         * @var array
         */
        public $pluginOptions = [];
        /**
         * Client options
         * @var array
         */
        public $clientOptions = [];

        /**
         * @inheritdoc
         */
        public function init()
        {
            $this->pluginOptions = ArrayHelper::merge($this->pluginOptions, $this->clientOptions);

            if (!$this->model->hasErrors()) {
                foreach ($this->model->{$this->initialAttribute} as $path) {
                    $this->pluginOptions['initialPreview'][]       = Html::img($path,
                            [
                                'class' => 'file-preview-image',
                                'style' => [
                                    'max-width'  => '100%;',
                                    'max-height' => '100%',
                                ],
                            ]).Html::hiddenInput(sprintf('%s[%s][]', $this->model->formName(),
                            $this->initialAttribute), $path);
                    $this->pluginOptions['initialPreviewConfig'][] = [
                        'caption'   => basename($path),
                        'frameAttr' => [],
                        'url'       => Url::to([
                            '/image-upload/delete',
                            'path'  => $path,
                            'token' => \Yii::$app->security->encryptByKey($path, Settings::valueOf('security.hash.token')),
                        ]),
                    ];
                }
            }

            parent::init();
        }
    }
