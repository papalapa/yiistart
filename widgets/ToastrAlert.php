<?php

    namespace papalapa\yiistart\widgets;

    use papalapa\yiistart\assets\CDN_CodeSeven_Toastr_Asset;
    use yii\base\Widget;
    use yii\helpers\Html;
    use yii\helpers\Json;

    /**
     * Class ToastrAlert
     * ToastrAlert widget renders a message from session flash.
     * First add toasters to session:
     * ```php
     * \Yii::$app->session->setFlash('mySuccessToastr', 'Hello world!');
     * \Yii::$app->session->setFlash('myErrorToastr', 'Failed!');
     * ```
     * Display them in app:
     * ```php
     * echo ToastrAlert::widget(['toastrTypes' => ['mySuccessToastr' => Toastr::TYPE_SUCCESS]]);
     * ```
     * Or send through ajax:
     * ```php
     * echo ToastrAlert::widget(['isAjax' => true, 'toastrTypes' => ['myErrorToastr' => Toastr::TYPE_ERROR]]);
     * ```
     * @author  Dmitriy Kim <mail@dmitriy.kim>
     * @package papalapa\yiistart\widgets
     */
    class ToastrAlert extends Widget
    {
        /**
         * @var bool
         */
        public $isAjax      = false;
        /**
         * @var array
         */
        public $types = [
            'error'   => Toastr::TYPE_ERROR,
            'success' => Toastr::TYPE_SUCCESS,
            'info'    => Toastr::TYPE_INFO,
            'warning' => Toastr::TYPE_WARNING,
        ];
        /**
         * Toastr plugin options
         * @var array
         */
        private $pluginOptions = [
            'closeButton'       => true,
            'debug'             => false,
            'newestOnTop'       => true,
            'progressBar'       => false,
            'preventDuplicates' => false,
            'onclick'           => null,
            'showDuration'      => '250',
            'hideDuration'      => '1000',
            'timeOut'           => '5000',
            'extendedTimeOut'   => '1000',
            'showEasing'        => 'swing',
            'hideEasing'        => 'linear',
            'showMethod'        => 'fadeIn',
            'hideMethod'        => 'fadeOut',
            'positionClass'     => 'toast-top-right',
        ];

        public function run()
        {
            CDN_CodeSeven_Toastr_Asset::register($this->view);

            $options = Json::htmlEncode($this->pluginOptions);

            foreach (\Yii::$app->session->getAllFlashes() as $type => $data) {
                if (isset($this->types[$type])) {
                    if ($this->isAjax) {
                        $html = [];
                        foreach ((array)$data as $message) {
                            if (is_array($message)) {
                                $message = implode(Html::tag('br'), $message);
                            }
                            $html[] = Html::script("toastr.{$this->types[$type]}('{$message}', null, {$options});");
                        }
                        \Yii::$app->session->removeFlash($type);

                        return implode(PHP_EOL, $html);
                    } else {
                        foreach ((array)$data as $message) {
                            if (is_array($message)) {
                                $message = implode(Html::tag('br'), $message);
                            }
                            $this->view->registerJs("toastr.{$this->types[$type]}('{$message}', {$options});");
                        }
                        \Yii::$app->session->removeFlash($type);
                    }
                }
            }
        }
    }
