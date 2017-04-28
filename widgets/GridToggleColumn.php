<?php

    namespace papalapa\yiistart\widgets;

    use yii\grid\DataColumn;
    use yii\helpers\Html;

    /**
     * Class GridToggleColumn
     * @package papalapa\yiistart\widgets
     */
    class GridToggleColumn extends DataColumn
    {
        /**
         * @var bool
         */
        public $encodeLabel = false;
        /**
         * @var string
         */
        public $format = 'raw';
        /**
         * @var array
         */
        public $filter = [0 => 'нет', 1 => 'да'];
        /**
         * @var array
         */
        public $contentOptions = ['class' => 'text-center'];
        /**
         * @var array
         */
        public $headerOptions = ['width' => 100];
        /**
         * Enable/disable ajax switching
         * @var bool
         */
        public $enableAjax = true;
        /**
         * @var string
         */
        public $labelTitle;
        /**
         * @var string
         */
        public $labelIco;

        /**
         * @inheritdoc
         */
        public function init()
        {
            $this->label = Html::tag('i', null, ['class' => $this->labelIco, 'data-toggle' => 'tooltip', 'title' => $this->labelTitle]);

            $this->value = function ($model, $key, $index, $column) {
                return $this->enableAjax ? $this->renderDynamic($model) : $this->renderStatic($model);
            };
        }

        /**
         * @param $model
         * @return string
         */
        public function renderStatic($model)
        {
            return is_null($model->{$this->attribute}) ? null : Html::tag('i', null, [
                'class' => $model->{$this->attribute} ?
                    'fa fa-check text-success' : 'fa fa-times-circle text-danger',
            ]);
        }

        /**
         * @param $model
         * @return string
         */
        public function renderDynamic($model)
        {
            \Yii::$app->view->registerJs("
                $(document).on('click', '.pjax-toggle-attribute-{$this->attribute}-handler a', function (event) {
                    var container = $(this).closest('[data-pjax-container]');
                    $.pjax.click(event, {
                        cache:               false,
                        skipOuterContainers: true,
                        container:           container,
                        push:                false,
                        replace:             false,
                        timeout:             10000,
                        scrollTo:            false
                    });
                });
            ");

            ob_start();

            echo \Yii::$app->view->renderFile('@vendor/papalapa/yiistart/widgets/views/grid-toggle-column.php', [
                'model'     => $model,
                'attribute' => $this->attribute,
            ]);

            return ob_get_clean();
        }
    }
