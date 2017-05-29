<?php

    namespace papalapa\yiistart\widgets;

    use papalapa\yiistart\modules\elements\models\Elements;
    use papalapa\yiistart\modules\settings\models\Settings;
    use yii\base\Widget;
    use yii\helpers\Html;

    /**
     * Class ElementsWidget
     * @package papalapa\yiistart\widgets
     */
    class ElementsWidget extends Widget
    {
        /**
         * Requested element`s alias
         * This priority is higher than id
         * @var
         */
        public $elementAlias;
        /**
         * Requested element`s id
         * @var
         */
        public $elementId;
        /**
         * @var Elements
         */
        protected $model;

        /**
         * @inheritdoc
         */
        public function init()
        {
            $id    = isset($this->elementId) ? (int)$this->elementId : null;
            $alias = isset($this->elementAlias) ? (string)$this->elementAlias : null;

            if (empty($id) && empty($alias)) {
                return null;
            }

            $this->model = \Yii::$app->db->cache(function () use ($id, $alias) {
                $query = Elements::find()->where(['is_active' => true])->andFilterWhere(['alias' => $alias])->andFilterWhere(['id' => $id]);

                return $query->one();
            }, Settings::paramOf('cache.duration.element', null));

            parent::init();
        }

        /**
         * Rendering element`s model as it`s value format
         * @return null|string
         */
        public function run()
        {
            if (is_null($this->model)) {
                \Yii::warning(sprintf('Используется несуществующий элемент "%s"', $this->elementAlias ? $this->elementAlias : $this->elementId));

                return null;
            }

            switch ($this->model->format) {
                case Elements::FORMAT_TEL:
                    return Html::a($this->model->text, sprintf('tel:%s', $this->model->text));
                case Elements::FORMAT_HTML:
                    return \Yii::$app->formatter->asHtml($this->model->text);
                case Elements::FORMAT_EMAIL:
                    return \Yii::$app->formatter->asEmail($this->model->text);
                case Elements::FORMAT_TEXT:
                    return \Yii::$app->formatter->asText($this->model->text);
                default:
                    return \Yii::$app->formatter->asRaw($this->model->text);
            }
        }
    }
