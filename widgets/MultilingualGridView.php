<?php

    namespace papalapa\yiistart\widgets;

    use papalapa\yiistart\modules\i18n\models\i18n;
    use yii\grid\GridView;

    /**
     * Class MultilingualGridView
     * @package papalapa\yiistart\widgets
     */
    class MultilingualGridView extends GridView
    {
        /**
         * Multilingual column names
         * @var
         */
        public $multilingualColumns = [];

        /**
         * @inheritdoc
         */
        public function init()
        {
            parent::init();

            $columns = [];
            foreach ($this->columns as $column) /* @var $column \yii\grid\DataColumn */ {
                $columns[] = $column;
                if ($column->hasProperty('attribute') && in_array($column->attribute, $this->multilingualColumns)) {
                    foreach (i18n::locales() as $locale) {
                        if (\Yii::$app->language <> $locale) {
                            $multilingualAttribute = sprintf('%s_%s', $column->attribute, $locale);
                            if ($this->filterModel->hasProperty($multilingualAttribute)) {
                                $multilingualColumn            = clone $column;
                                $multilingualColumn->attribute = $multilingualAttribute;
                                $multilingualColumn->label     = $this->filterModel->getAttributeLabel($multilingualAttribute);
                                $multilingualColumn->value     = $this->filterModel->$multilingualAttribute;
                                $multilingualColumn->filter    = false;
                                $columns[]                     = $multilingualColumn;
                            }
                        }
                    }
                }
            }
            $this->columns = $columns;
        }
    }
