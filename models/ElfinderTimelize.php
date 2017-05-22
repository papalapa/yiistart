<?php

    namespace papalapa\yiistart\models;

    use mihaildev\elfinder\PluginInterface;
    use yii\helpers\FileHelper;

    /**
     * Class ElfinderTimelize
     * @package papalapa\yiistart\models
     */
    class ElfinderTimelize extends PluginInterface
    {
        /**
         * Run plugin on next events
         * @var array
         */
        public $bind = [
            'upload.presave' => 'timelize',
        ];

        /**
         * Name of plugin
         * @return string
         */
        public function getName()
        {
            return (new \ReflectionClass(__CLASS__))->getShortName();
        }

        /**
         * @param $path
         * @param $name
         * @param $src
         * @param $elfinder
         * @param $volume
         * @return bool
         */
        public function timelize(&$path, &$name, $src, $elfinder, $volume)
        {
            if (!$this->isEnable($volume)) {
                return false;
            }

            $extension = pathinfo($name, PATHINFO_EXTENSION);

            if (!$extension) {
                $extensions = FileHelper::getExtensionsByMimeType(FileHelper::getMimeType($name));
                $extension  = array_shift($extensions);
            }

            $time = time();

            $name = sprintf('%s.%s', \Yii::$app->formatter->asDate($time, 'YYYYMMdd_HHmmss__') . $time, $extension);

            return true;
        }
    }
