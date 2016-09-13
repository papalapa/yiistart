<?php

    namespace vendor\papalapa\yii2\widgets;

    use yii\bootstrap\ActiveField;
    use yii\bootstrap\Html;
    use yii\helpers\ArrayHelper;

    /**
     * Class BootstrapActiveField
     * @package vendor\papalapa\yii2\widgets
     */
    class BootstrapActiveField extends ActiveField
    {
        #public $checkboxTemplate = "<div class=\"checkbox\">\n{input}\n{label}\n{error}\n{hint}\n</div>";
        #public $radioTemplate    = "<div class=\"radio\">\n{input}\n{label}\n{error}\n{hint}\n</div>";

        /**
         * @var string the template for checkboxes in default layout
         */
        public $checkboxTemplate = "<div class=\"checkbox\">{input}\n{label}\n{error}\n{hint}\n</div>";
        /**
         * @var string the template for radios in default layout
         */
        public $radioTemplate = "<div class=\"radio\">{input}\n{label}\n{error}\n{hint}\n</div>";
        /**
         * @var string the template for checkboxes in horizontal layout
         */
        public $horizontalCheckboxTemplate = "{beginWrapper}\n<div class=\"checkbox\">{input}\n{label}\n</div>\n{error}\n{endWrapper}\n{hint}";
        /**
         * @var string the template for radio buttons in horizontal layout
         */
        public $horizontalRadioTemplate = "{beginWrapper}\n<div class=\"radio\">{input}\n{label}\n</div>\n{error}\n{endWrapper}\n{hint}";
        /**
         * @var string the template for inline checkboxLists
         */
        public $inlineCheckboxListTemplate = "{label}213\n{beginWrapper}\n{input}\n{error}\n{endWrapper}\n{hint}";
        /**
         * @var string the template for inline radioLists
         */
        public $inlineRadioListTemplate = "{label}\n{beginWrapper}\n{input}\n{error}\n{endWrapper}\n{hint}";

        /**
         * @inheritdoc
         */
        public function radioList($items, $options = [])
        {
            if ($this->inline) {
                if (!isset($options['template'])) {
                    $this->template = $this->inlineCheckboxListTemplate;
                } else {
                    $this->template = $options['template'];
                    unset($options['template']);
                }
                if (!isset($options['itemOptions'])) {
                    $options['itemOptions'] = [
                        'labelOptions' => ['class' => 'checkbox-inline'],
                    ];
                }
            }
            if (!isset($options['item'])) {
                $options['item'] = function ($index, $label, $name, $checked, $value) {
                    return implode("\n", [
                        Html::beginTag('div', ['class' => $this->inline ? 'radio-inline' : 'radio']),
                        Html::input('radio', $name, $value,
                            ArrayHelper::merge(['id' => $name.'['.$value.']'], $checked ? ['checked' => 'checked'] : [])),
                        Html::tag('label', $label, ['class' => 'radio', 'for' => $name.'['.$value.']']),
                        Html::endTag('div'),
                    ]);
                };
            }
            parent::radioList($items, $options);

            return $this;
        }

        /**
         * @inheritdoc
         */
        public function checkboxList($items, $options = [])
        {
            if ($this->inline) {
                if (!isset($options['template'])) {
                    $this->template = $this->inlineCheckboxListTemplate;
                } else {
                    $this->template = $options['template'];
                    unset($options['template']);
                }
                if (!isset($options['itemOptions'])) {
                    $options['itemOptions'] = [
                        'labelOptions' => ['class' => 'checkbox-inline'],
                    ];
                }
            }
            if (!isset($options['item'])) {
                $options['item'] = function ($index, $label, $name, $checked, $value) {
                    return implode("\n", [
                        Html::beginTag('div', ['class' => $this->inline ? 'checkbox-inline' : 'checkbox']),
                        Html::input('checkbox', $name, $value,
                            ArrayHelper::merge(['id' => $name.'['.$value.']'], $checked ? ['checked' => 'checked'] : [])),
                        Html::tag('label', $label, ['class' => 'checkbox', 'for' => $name.'['.$value.']']),
                        Html::endTag('div'),
                    ]);
                };
            }
            parent::checkboxList($items, $options);

            return $this;
        }
    }