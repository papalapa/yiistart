<?php

    namespace papalapa\yiistart\models;

    use mihaildev\elfinder\PluginInterface;
    use yii\helpers\FileHelper;
    use yii\helpers\StringHelper;

    /**
     * Class ElfinderShortenize
     * @package papalapa\yiistart\models
     */
    class ElfinderShortenize extends PluginInterface
    {
        /**
         * Run plugin on next events
         * @var array
         */
        public $bind = [
            'upload.presave' => 'shortenize',
        ];
        /**
         * Max name length
         * @var int
         */
        public $length = 16;

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
        public function shortenize(&$path, &$name, $src, $elfinder, $volume)
        {
            if (!$this->isEnable($volume)) {
                return false;
            }

            $extension = pathinfo($name, PATHINFO_EXTENSION);
            $filename  = pathinfo($name, PATHINFO_FILENAME);

            if (!$extension) {
                $extensions = FileHelper::getExtensionsByMimeType(FileHelper::getMimeType($name));
                $extension  = array_shift($extensions);
            }

            $name = sprintf('%s.%s', StringHelper::truncate($filename, $this->length, null), $extension);

            return true;
        }
    }
