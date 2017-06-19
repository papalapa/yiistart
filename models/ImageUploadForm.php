<?php

    namespace papalapa\yiistart\models;

    use papalapa\yiistart\helpers\FileHelper;
    use yii\base\Model;

    /**
     * Class ImageUploadForm
     * @property string $path
     * @package papalapa\yiistart\models
     */
    class ImageUploadForm extends Model
    {
        /**
         * @var \yii\web\UploadedFile
         */
        public $image;
        /**
         * Local path relative to frontend/web
         * @var
         */
        protected $path;

        /**
         * @return array
         */
        public function rules()
        {
            return [
                ['image', 'file', 'extensions' => 'png, jpg, jpeg'],
            ];
        }

        /**
         * @param $storage - upload directory
         * @return bool
         */
        public function upload($storage)
        {
            if ($this->validate()) {
                /**
                 * Getting extension
                 */
                if (!$extension = pathinfo($this->image->name, PATHINFO_EXTENSION)) {
                    $extensions = FileHelper::getExtensionsByMimeType(FileHelper::getMimeType($this->image->tempName));
                    $extension  = array_shift($extensions);
                }
                /**
                 * Hashing filename
                 */
                $name = sprintf('%d_%s.%s', time(), md5(microtime(true)), $extension);
                /**
                 * Getting path to save file
                 */
                $path = \Yii::getAlias('@frontend/web'.DIRECTORY_SEPARATOR.$storage);

                if (!FileHelper::exists($path)) {
                    FileHelper::mkdir($path);
                } elseif (!FileHelper::is_dir($path)) {
                    \Yii::error('Storage is exists but it is not a directory');

                    return false;
                }

                if ($this->image->saveAs($path.DIRECTORY_SEPARATOR.$name)) {
                    $this->path = DIRECTORY_SEPARATOR.$storage.DIRECTORY_SEPARATOR.$name;

                    return true;
                }
            }

            return false;
        }

        /**
         * @return string
         */
        public function getPath()
        {
            return $this->path;
        }
    }
