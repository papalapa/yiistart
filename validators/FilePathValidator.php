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
         * @param \yii\base\Model $model
         * @param string          $attribute
         * @throws InvalidConfigException
         */
        public function validateAttribute($model, $attribute)
        {
            $camel                  = Inflector::camel2id((new \ReflectionClass($model))->getShortName(), '_');
            $this->uploadController = Settings::paramOf(sprintf('%s.upload.controller', $camel), $this->uploadController);

            $basePath = ArrayHelper::getValue(\Yii::$app->controllerMap, [$this->uploadController, 'root', 'basePath'], false);
            $path     = ArrayHelper::getValue(\Yii::$app->controllerMap, [$this->uploadController, 'root', 'path'], false);

            if (!$basePath || !$path) {
                throw new InvalidConfigException('File uploader is not defined on "controllerMap" in config.php');
            }

            $dirname = Inflector::camel2id((new \ReflectionClass($model))->getShortName(), '_');

            if (FileHelper::normalizePath($path.DIRECTORY_SEPARATOR.$dirname) <> pathinfo($model->$attribute, PATHINFO_DIRNAME)) {
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
