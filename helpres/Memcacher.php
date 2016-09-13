<?php

    namespace vendor\papalapa\yii2\helpers;

    use yii;
    use yii\caching\DbDependency;
    use yii\caching\Dependency;
    use yii\caching\MemCache;
    use yii\caching\TagDependency;

    /**
     * Class Memcacher
     * @package vendor\papalapa\yii2\helpers
     */
    class Memcacher extends MemCache
    {
        /**
         * @param string     $key        - cache name
         * @param Dependency $dependency - cache dependency
         * @param int        $duration   - time to live
         * @param callable   $function   - callable function to retrieve data
         * @param array      $params     - function params
         * @return bool|mixed
         */
        public static function cacheOut($key, $dependency, $duration = 0, callable $function, $params = [])
        {
            if (false !== $cache = self::cache()->get($key)) {
                return $cache;
            }

            if (!is_callable($function, false)) {
                throw new \InvalidArgumentException('Function argument must be callable.');
            }

            $cache = call_user_func_array($function, (array)$params);

            self::cache()->set($key, $cache, $duration, $dependency);

            return $cache;
        }

        /**
         * @return yii\caching\Cache
         */
        public static function cache()
        {
            return Yii::$app->cache;
        }

        /**
         * TagDependency invalidate
         * @param string|array $tags
         */
        public static function tagInvalidate($tags)
        {
            TagDependency::invalidate(Yii::$app->cache, $tags);
        }

        /**
         * TagDependency
         * @param string|array $tags
         * @return TagDependency
         */
        public static function tagDependency($tags)
        {
            return new TagDependency(['tags' => $tags]);
        }

        /**
         * DbDependency
         * @param string $sql
         * @param array  $params
         * @return DbDependency
         */
        public static function dbDependency($sql, $params = [])
        {
            return new DbDependency(['sql' => $sql, 'params' => $params]);
        }
    }