<?php

    namespace vendor\papalapa\yii2start\helpers;

    use yii;

    /**
     * Class Pixer
     * @package vendor\papalapa\yii2start\helpers
     */
    class Pixer extends BasePixer
    {
        /**
         * @return bool|string
         */
        protected function watermarkPath()
        {
            return Yii::getAlias('@vendor/papalapa/yii2start/assets/img/default.png');
        }

        /**
         * @param $path
         * @return bool|string
         */
        protected function findFrom($path)
        {
            // TODO: Implement findFrom() method.
            return Yii::getAlias("@frontend/web{$path}");
        }

        protected function saveTo($path)
        {
            // TODO: Implement saveTo() method.
            return Yii::getAlias("@webroot{$path}");
        }
    }