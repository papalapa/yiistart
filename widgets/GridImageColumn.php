<?php

    namespace papalapa\yiistart\widgets;

    use yii\db\ActiveRecord;
    use yii\grid\DataColumn;
    use yii\helpers\Html;

    /**
     * Class GridImageColumn
     * @package papalapa\yiistart\widgets
     */
    class GridImageColumn extends DataColumn
    {
        /**
         * @var string
         */
        public $format = 'html';
        /**
         * If wrap image in link tag is needed
         * @var bool
         */
        public $linked = true;
        /**
         * @var array
         */
        public $filter = [0 => 'нет', 1 => 'есть'];
        /**
         * Image height
         * @var int
         */
        public $height = 40;

        /**
         * @inheritdoc
         */
        public function init()
        {
            $this->content = function ($model) /* @var $model ActiveRecord */ {
                if ($model->hasAttribute($this->attribute) && $model->getAttribute($this->attribute)) {
                    $image = Html::img($model->getAttribute($this->attribute), ['height' => $this->height]);
                    if ($this->linked) {
                        $siteUrlManager          = clone \Yii::$app->urlManager;
                        $siteUrlManager->baseUrl = '/';

                        return Html::a($image, $siteUrlManager->createUrl([$model->getAttribute($this->attribute)]),
                            ['data-pjax' => 0, 'target' => '_blank']);
                    }

                    return $image;
                }

                return null;
            };
        }
    }
