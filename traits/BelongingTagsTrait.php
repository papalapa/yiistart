<?php

    namespace papalapa\yiistart\traits;

    use papalapa\yiistart\models\BelongingTags;

    /**
     * Trait BelongingTagsTrait
     * @property BelongingTags[] $belongingTags
     * @package papalapa\yiistart\traits
     */
    trait BelongingTagsTrait
    {
        /**
         * @var null
         */
        public $_tags = null;

        /**
         * @return \yii\db\ActiveQuery
         */
        final public function getBelongingTags()
        {
            /* @var \yii\db\ActiveRecord $this */
            return $this->hasMany(BelongingTags::className(), ['content_id' => 'id'])
                ->andFilterWhere(['content_type' => static::contentType()])
                ->andWhere(['is_outdated' => false])
                ->orderBy(['[[order]]' => SORT_ASC])->addOrderBy(['[[tag]]' => SORT_ASC]);
        }
    }
