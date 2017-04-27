<?php

    namespace papalapa\yiistart\widgets;

    use papalapa\yiistart\assets\CDN_Carhartl_JqueryCookie_Asset;
    use papalapa\yiistart\models\User;
    use yii\base\InvalidConfigException;
    use yii\base\Widget;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\helpers\Url;

    /**
     * Class ControlButtonsPanel
     * @package papalapa\yiistart\widgets
     */
    class ControlButtonsPanel extends Widget
    {
        public $items;
        public $options = [];

        /**
         * @throws InvalidConfigException
         */
        public function init()
        {
            parent::init();

            if (!is_array($this->items)) {
                throw new InvalidConfigException('Property "items" must be an array in widget ' . __CLASS__);
            }

            if (!is_array($this->options)) {
                throw new InvalidConfigException('Property "options" must be an array in widget ' . __CLASS__);
            }
        }

        /**
         * @return null|string
         */
        public function run()
        {
            if (User::isGuest() || User::identity()->role < User::ROLE_MANAGER || !$this->checkAll()) {
                return null;
            }

            CDN_Carhartl_JqueryCookie_Asset::register($this->view);

            return implode(null, [
                Html::beginTag('div', ['class' => 'well']),
                $this->renderItems(),
                Html::endTag('div'),
            ]);
        }

        /**
         * @return bool
         */
        private function checkAll()
        {
            foreach ($this->items as $index => $item) {
                $rule   = is_int($index) ? ArrayHelper::remove($item, 'rule', false) : $index;
                $params = ArrayHelper::remove($item, 'params');
                if (false === $rule || !\Yii::$app->user->can($rule, $params)) {
                    unset($this->items[$index]);
                }
            }

            return !empty($this->items);
        }

        /**
         * @return string
         */
        private function renderItems()
        {
            $html = [];
            foreach ($this->items as $item) {
                $url    = ArrayHelper::remove($item, 'url');
                $title  = ArrayHelper::remove($item, 'title');
                $ico    = ArrayHelper::remove($item, 'ico');
                $html[] = Html::a(($ico ? Html::tag('i', null, ['class' => $ico]) . ' ' : null) . $title, Url::to($url), $item);
            }

            return implode(PHP_EOL, [
                Html::beginTag('div', $this->options),
                implode(PHP_EOL, $html),
                Html::endTag('div'),
            ]);
        }
    }
