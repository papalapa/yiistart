<?php

    namespace papalapa\yiistart\widgets;

    use papalapa\yiistart\models\MultilingualActiveRecord;
    use papalapa\yiistart\modules\i18n\models\i18n;
    use yii\db\ActiveRecord;
    use yii\grid\DataColumn;
    use yii\helpers\Html;

    /**
     * Class GridMetatagsColumn
     * @package papalapa\yiistart\widgets
     */
    class GridMetatagsColumn extends DataColumn
    {
        /**
         * @var string
         */
        public $label = '<span data-toggle="tooltip" title="Мета-теги"><i class="fa fa-globe"></i></span>';
        /**
         * @var bool
         */
        public $encodeLabel = false;
        /**
         * @var array
         */
        public $attributes = [
            'title'       => 'info-circle',
            'description' => 'question-circle',
            'keywords'    => 'key',
        ];

        /**
         * @inheritdoc
         */
        public function init()
        {
            $this->content = function ($model, $key, $index, $column) /* @var $model ActiveRecord|MultilingualActiveRecord */ {
                return $this->renderAttributes($model);
            };
        }

        /**
         * Rendering all of attributes
         * @param $model ActiveRecord|MultilingualActiveRecord
         * @return string
         */
        protected function renderAttributes($model)
        {
            $html = [];

            foreach ($this->attributes as $attribute => $ico) {
                if ($model->hasAttribute($attribute)) {
                    if ($model->getBehavior('MultilingualBehavior')) {
                        foreach (i18n::locales() as $locale) {
                            $html[] = $this->renderAttribute($model, $attribute, $ico, $locale);
                        }
                    } else {
                        $html[] = $this->renderAttribute($model, $attribute, $ico);
                    }
                }
            }

            return implode(' ', $html);
        }

        /**
         * Rendering one tag
         * @param             $model     ActiveRecord|MultilingualActiveRecord
         * @param string      $attribute - model attribute label
         * @param string      $ico       - attribute ico
         * @param string|null $locale    - using locale
         * @return string
         */
        protected function renderAttribute($model, $attribute, $ico, $locale = null)
        {
            $html[] = Html::beginTag('span', [
                'class'       => $model->{$attribute.($locale ? sprintf('_%s', $locale) : null)} ? 'label label-success' : 'label label-danger',
                'title'       => $model->getAttributeLabel($attribute).($locale ? sprintf(' (%s)', $locale) : null),
                'data-toggle' => 'tooltip',
            ]);
            $html[] = Html::tag('i', null, ['class' => sprintf('fa fa-%s', $ico)]);
            $html[] = $locale ? ' '.$locale : null;
            $html[] = Html::endTag('span');

            return implode(null, $html);
        }
    }
