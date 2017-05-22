<?php

    namespace papalapa\yiistart\widgets;

    use yii\helpers\Html;
    use yii\helpers\Inflector;

    /**
     * Class ElfinderImageInput
     * @package backend\widgets
     */
    class ElfinderImageInput extends \mihaildev\elfinder\InputFile
    {
        /**
         * Using language
         * @var string
         */
        public $language = 'ru';
        /**
         * File filter
         * @link https://github.com/Studio-42/elFinder/wiki/Client-configuration-options#wiki-onlyMimes
         * @var string
         */
        public $filter = 'image';
        /**
         * Button settings
         * @var string
         */
        public $buttonTag     = 'button';
        public $buttonName    = 'Выбрать';
        public $buttonOptions = ['class' => 'btn btn-default'];
        public $width         = 'auto';
        public $height        = 'auto';
        /**
         * Templato to render input
         * @var string
         */
        public $template = '{input}{button}';
        /**
         * Controller to catch requests
         * @file /app/backend/config/elfinder.php
         * @var string
         */
        public $controller = 'upload';
        public $path;
        /**
         * Allow or disallow multiple file inputs
         * @var bool
         */
        public $multiple = false;
        public $startPath;
        /**
         * Form input options
         * @var array
         */
        public $options = [
            'class'    => 'form-control',
            'readonly' => 'readonly',
        ];

        /**
         * @inheritdoc
         */
        public function init()
        {
            if ($this->model) {
                $this->path = Inflector::camel2id((new \ReflectionClass($this->model))->getShortName(), '_');
            }

            $this->template = implode(null, [
                Html::beginTag('div', ['class' => 'input-group elfinder-image-widget']),
                Html::beginTag('span', ['class' => 'input-group-btn']),
                Html::img(null, ['class' => 'btn btn-default elfinder-image', 'data-src' => $this->model->{$this->attribute}, 'height' => 34]),
                Html::endTag('span'),
                '{input}',
                Html::beginTag('span', ['class' => 'input-group-addon']),
                Html::a(Html::tag('i', null, ['class' => 'fa fa-times']), 'javascript:void(0)', [
                    'class' => 'text-danger elfinder-cleaner',
                    'title' => 'Очистить',
                ]),
                Html::endTag('span'),
                Html::beginTag('span', ['class' => 'input-group-btn']),
                '{button}',
                Html::endTag('span'),
                Html::endTag('div'),
            ]);

            parent::init();
        }

        /**
         * @inheritdoc
         */
        public function run()
        {
            $this->registerClientScript();

            parent::run();
        }

        /**
         * Registering javascript
         */
        protected function registerClientScript()
        {
            $this->view->registerJs("
                $('.elfinder-image-widget').each(function(i){
                    var elfinderWidget = $(this);
                    var elfinderImage = elfinderWidget.find('.elfinder-image');
                    var elfinderCleaner = elfinderWidget.find('.elfinder-cleaner');
                    /**
                    * Show pic on page load 
                    */
                    if (elfinderImage.attr('data-src')){
                        elfinderImage.attr('src', elfinderImage.attr('data-src'));
                    }
                    else {
                        elfinderImage.hide();
                        elfinderCleaner.closest('span').hide();
                    }
                    /**
                    * Add trigger on change file input 
                    */
                    elfinderWidget.find('input').on('change', function(){
                        if ($(this).val()){
                            elfinderImage.attr('src', $(this).val());
                            elfinderImage.show();
                            elfinderCleaner.closest('span').show();
                        }
                        else {
                            elfinderImage.removeAttr('src');
                            elfinderImage.hide();
                            elfinderCleaner.closest('span').hide();
                        }
                    });
                    /**
                    * Add trigger to open image in new window 
                    */
                    elfinderImage.on('click', function(){
                        window.open($(this).attr('src'));
                    });
                    /**
                    * Add trigger to clearing input 
                    */
                    elfinderCleaner.on('click', function(){
                        elfinderWidget.find('input').val('').trigger('change');
                    });                    
                });
            ");
        }
    }
