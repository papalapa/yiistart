<?php

    namespace papalapa\yiistart\models;

    use mihaildev\elfinder\PluginInterface;
    use yii\helpers\FileHelper;

    /**
     * Class ElfinderHashenize
     * @package papalapa\yiistart\models
     */
    class ElfinderHashenize extends PluginInterface
    {
        /**
         * Run plugin on next events
         * @var array
         */
        public $bind = [
            'upload.presave' => 'hashenize',
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
        public function hashenize(&$path, &$name, $src, $elfinder, $volume)
        {
            if (!$this->isEnable($volume)) {
                return false;
            }

            $extension = pathinfo($name, PATHINFO_EXTENSION);

            if (!$extension) {
                $extensions = FileHelper::getExtensionsByMimeType(FileHelper::getMimeType($name));
                $extension  = array_shift($extensions);
            }

            $name = sprintf('%s.%s', md5(microtime(true)), $extension);

            return true;
        }
    }
