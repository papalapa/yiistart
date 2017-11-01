<?php

    namespace papalapa\yiistart\widgets;

    use yii\grid\DataColumn;
    use yii\helpers\Html;

    /**
     * Class GridOrderColumn
     * @package papalapa\yiistart\widgets
     */
    class GridOrderColumn extends DataColumn
    {
        /**
         * @var array
         */
        public $headerOptions = ['width' => '100'];
        /**
         * @var string
         */
        public $format = 'raw';
        /**
         * @var bool
         */
        public $encodeLabel = false;
        /**
         * @var array
         */
        public $contentOptions = ['class' => 'text-center'];
        /**
         * Enable/disable ajax switching
         * @var bool
         */
        public $enableAjax = true;
        /**
         * @var string
         */
        public $label = '<i class="fa fa-sort-numeric-asc" data-toggle="tooltip" title="Порядковый номер"></i>';
        /**
         * @var array
         */
        public $filterInputOptions = ['class' => 'form-control', 'type' => 'number', 'min' => '0'];
        /**
         * @var string
         */
        public $labelTitle = 'Порядок';
        /**
         * @var string
         */
        public $labelIco = 'fa fa-sort';

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
            return is_null($model->{$this->attribute}) ? null : Html::tag('span', $model->{$this->attribute}, ['class' => 'badge']);
        }

        /**
         * @param $model
         * @return string
         */
        public function renderDynamic($model)
        {
            \Yii::$app->view->registerJs("
                $(document).on('click', '.pjax-reorder-attribute-{$this->attribute}-handler a', function (event) {
                    var container = '#' + $(this).closest('[data-pjax-container]').attr('id');
                    $.pjax.click(event, {
                        cache:               false,
                        push:                false,
                        replace:             false,
                        timeout:             10000,
                        scrollTo:            false,
                        container:           container,
                        skipOuterContainers: true
                    });
                });
            ");

            ob_start();

            echo \Yii::$app->view->renderFile('@vendor/papalapa/yiistart/widgets/views/grid-order-column.php', [
                'model'     => $model,
                'attribute' => $this->attribute,
            ]);

            return ob_get_clean();
        }
    }
