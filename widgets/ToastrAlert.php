<?php
    namespace papalapa\yiistart\widgets;

    use yii;
    use yii\base\Widget;
    use yii\helpers\Html;
    use yii\helpers\Json;

    /**
     * Class ToastrAlert
     * @package papalapa\yiistart\widgets
     */
    class ToastrAlert extends Widget
    {
        public  $isAjax        = false;
        public  $toastrTypes   = [
            'error'   => Toastr::TYPE_ERROR,
            'success' => Toastr::TYPE_SUCCESS,
            'info'    => Toastr::TYPE_INFO,
            'warning' => Toastr::TYPE_WARNING,
        ];
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
            ToastrAsset::register($this->view);

            $session = \Yii::$app->session;
            $flashes = $session->getAllFlashes();
            $options = Json::htmlEncode($this->pluginOptions);

            foreach ($flashes as $type => $data) {
                if (isset($this->toastrTypes[$type])) {
                    if ($this->isAjax) {
                        $html = [];
                        foreach ((array)$data as $message) {
                            if (is_array($message)) {
                                $message = implode(Html::tag('br'), $message);
                            }
                            $html[] = Html::script("toastr.{$this->toastrTypes[$type]}('{$message}', null, {$options});");
                        }
                        $session->removeFlash($type);

                        return implode(PHP_EOL, $html);
                    } else {
                        foreach ((array)$data as $message) {
                            if (is_array($message)) {
                                $message = implode(Html::tag('br'), $message);
                            }
                            $this->view->registerJs("toastr.{$this->toastrTypes[$type]}('{$message}', {$options});");
                        }
                        $session->removeFlash($type);
                    }
                }
            }
        }
    }
