<?php

    namespace papalapa\yiistart\validators;

    use papalapa\yiistart\helpers\FileHelper;
    use papalapa\yiistart\modules\settings\models\Settings;
    use yii\base\InvalidConfigException;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Inflector;
    use yii\validators\FileValidator;
    use yii\validators\Validator;
    use yii\web\UploadedFile;

    /**
     * Class FilePathValidator
     * Check uploading path and convert attribute into the UploadedFile instance to run next file validation
     * Call this validator before FileValidator
     * @package papalapa\yiistart\validators
     */
    class FilePathValidator extends Validator
    {
        /**
         * Upload default controller
         * To change controller add param %model%.upload.controller
         * @var string
         */
        public $uploadController = 'upload';
        /**
         * Directory name of this model to store files
         * @var
         */
        public $storeDirectory;
        /**
         * Allow or disallow sub directories
         * For example: $storeStrict = true, allowed /uploads/news, disallowed /uploads/news/1234
         * @var bool
         */
        public $strictPath = false;

        /**
         * @param \yii\base\Model $model
         * @param string          $attribute
         * @throws InvalidConfigException
         */
        public function validateAttribute($model, $attribute)
        {
            if ($model->{$attribute} instanceof UploadedFile) {
                $model->{$attribute} = $model->{$attribute}->name;
            }

            // get name of model in camel case
            $modelName = Inflector::camel2id((new \ReflectionClass($model))->getShortName(), '_');

            // search for individual controller name to handle file uploading in params.php
            $this->uploadController = Settings::paramOf(sprintf('%s.upload.controller', $modelName), $this->uploadController);

            // base path of webroot
            $basePath = ArrayHelper::getValue(\Yii::$app->controllerMap, [$this->uploadController, 'root', 'basePath'], false);

            // path for uploading
            $path = ArrayHelper::getValue(\Yii::$app->controllerMap, [$this->uploadController, 'root', 'path'], false);

            if (false === $basePath || false === $path) {
                throw new InvalidConfigException('File uploader is not defined on "controllerMap" in config.php');
            }

            // path for store files for current model
            $this->storeDirectory = $this->storeDirectory ? : $modelName;

            // directory name of uploaded file
            $uploadDirname = pathinfo($model->$attribute, PATHINFO_DIRNAME);

            // directory name for storing file
            $storeDirname = FileHelper::normalizePath($path.DIRECTORY_SEPARATOR.$this->storeDirectory);

            // in strict mode $uploadDirname must have an equivalent value with $storeDirname
            // otherwise $uploadDirname value must starts with $storeDirname
            if (
                ($this->strictPath && strcmp($uploadDirname, $storeDirname) <> 0)
                || 0 !== strpos($uploadDirname, $storeDirname)
            ) {
                return $this->addError($model, $attribute, 'Путь к файлу не соответствует указанной модели', $params = []);
            }

            $file = FileHelper::normalizePath($basePath.DIRECTORY_SEPARATOR.$model->$attribute);

            if (!FileHelper::exists($file) || !FileHelper::is_file($file)) {
                return $this->addError($model, $attribute, 'Файл не найден', $params = []);
            }

            foreach ($model->getActiveValidators($attribute) as $validator) {
                if ($validator instanceof FileValidator) {
                    $model->$attribute = new UploadedFile([
                        'tempName' => $file,
                        'name'     => $model->$attribute,
                        'size'     => FileHelper::size($file),
                        'type'     => FileHelper::getMimeTypeByExtension($file),
                    ]);
                    break;
                }
            }
        }
    }
