<?php

    namespace papalapa\yiistart\helpers;

    use yii;

    /**
     * Class Pixer
     * @package papalapa\yiistart\helpers
     */
    class Pixer extends BasePixer
    {
        /**
         * @return bool|string
         */
        protected function watermarkPath()
        {
            return Yii::getAlias('@vendor/papalapa/yiistart/assets/img/default.png');
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