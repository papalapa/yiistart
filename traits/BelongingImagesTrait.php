<?php

    namespace papalapa\yiistart\traits;

    use papalapa\yiistart\models\BelongingImages;

    /**
     * Trait BelongingImagesTrait
     * @property BelongingImages[] $belongingImages
     * @package papalapa\yiistart\traits
     */
    trait BelongingImagesTrait
    {
        /**
         * @var null
         */
        public $_images = null;

        /**
         * @return \yii\db\ActiveQuery
         */
        final public function getBelongingImages()
        {
            /* @var \yii\db\ActiveRecord $this */
            return $this->hasMany(BelongingImages::className(), ['content_id' => 'id'])
                        ->andFilterWhere(['content_type' => static::contentType()])
                        ->andWhere(['is_outdated' => false])
                        ->orderBy(['[[order]]' => SORT_ASC]);
        }
    }
