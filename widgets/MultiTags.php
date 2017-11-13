<?php

    namespace papalapa\yiistart\widgets;

    use kartik\select2\Select2;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Url;
    use yii\web\JsExpression;
    use yii\web\Response;

    /**
     * Class MultiTags
     * @package papalapa\yiistart\widgets
     */
    class MultiTags extends Select2
    {
        public $theme = self::THEME_KRAJEE;
        public $size  = self::MEDIUM;
        /**
         * Show toggling all link to remove all tags
         * @var bool
         */
        public $showToggleAll = false;
        public $options       = [
            'language'    => 'ru',
            'placeholder' => 'начинайте печатать здесь ...',
            'multiple'    => true,
        ];
        /**
         * Plugin options
         * @var array
         */
        public $pluginOptions = [
            'allowClear'         => true,
            'tags'               => true,
            'tokenSeparators'    => ["\n", '.', ','],
            'minimumInputLength' => 2,
            'ajax'               => [
                'url'      => null,
                'cache'    => true,
                'delay'    => 250,
                'dataType' => Response::FORMAT_JSON,
            ],
        ];
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
            parent::init();

            $this->pluginOptions = ArrayHelper::merge($this->pluginOptions, $this->clientOptions);

            /* @var $model \papalapa\yiistart\models\ContainingActiveRecord */
            $model   = $this->model;
            $content = $model->contentType();

            /**
             * Using TagsAjaxController action
             * @see \papalapa\yiistart\controllers\TagsAjaxController
             */
            $this->pluginOptions['ajax']['url']            = Url::to(['/tags-ajax']);
            $this->pluginOptions['ajax']['data']           = new JsExpression("function(params){ return {tag: params.term, content: '{$content}'}}");
            $this->pluginOptions['ajax']['processResults'] = new JsExpression("function(data, page){ return {results: data.items}}");
        }
    }
