<?php

    namespace papalapa\yiistart\models;

    use omgdef\multilingual\MultilingualTrait;
    use yii\db\ActiveQuery;

    /**
     * Class MultilingualActiveQuery
     * @see     MultilingualActiveRecord
     * @package papalapa\yiistart\models
     */
    class MultilingualActiveQuery extends ActiveQuery
    {
        use MultilingualTrait;
    }
