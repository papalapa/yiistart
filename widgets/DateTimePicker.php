<?php

    namespace papalapa\yiistart\widgets;

    use yii\helpers\ArrayHelper;

    /**
     * Class DatePicker
     * @package papalapa\yiistart\widgets
     */
    class DateTimePicker extends \kartik\widgets\DateTimePicker
    {
        /**
         * @var array
         */
        public $clientOptions = [];
        /**
         * @var array
         */
        public $pluginOptions = [
            'format'         => 'yyyy-mm-dd', // default format
            'todayBtn'       => true, // show today button
            'todayHighlight' => true, // highlighting current date
            'autoclose'      => true, // close after select
            'minView'        => 2, // disallow time select
            'weekStart'      => 1, // start with monday
        ];

        /**
         * @inheritdoc
         */
        public function init()
        {
            $this->pluginOptions['language'] = \Yii::$app->language;
            $this->pluginOptions             = ArrayHelper::merge($this->pluginOptions, $this->clientOptions);

            parent::init();
        }
    }
