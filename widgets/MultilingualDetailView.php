<?php

    namespace papalapa\yiistart\widgets;

    use papalapa\yiistart\models\MultilingualActiveRecord;
    use papalapa\yiistart\modules\i18n\models\i18n;
    use yii\db\ActiveRecord;
    use yii\widgets\DetailView;

    /**
     * Class MultilingualDetailView
     * @property ActiveRecord|MultilingualActiveRecord $model
     * @package papalapa\yiistart\widgets
     */
    class MultilingualDetailView extends DetailView
    {
        /**
         * @var ActiveRecord|MultilingualActiveRecord
         */
        public $model;

        /**
         * @inheritdoc
         */
        public function init()
        {
            parent::init();

            $attributes = [];
            foreach ($this->attributes as $item) {
                $attributes[] = $item;
                foreach (i18n::locales() as $locale) {
                    if (\Yii::$app->language <> $locale) {
                        $attribute = sprintf('%s_%s', $item['attribute'], $locale);
                        if ($this->model->hasProperty($attribute)) {
                            $attributes[] = array_replace($item, [
                                'attribute' => $attribute,
                                'label'     => $this->model->getAttributeLabel($attribute),
                                'value'     => $this->model->$attribute,
                            ]);
                        }
                    }
                }
            }
            $this->attributes = $attributes;
        }
    }
