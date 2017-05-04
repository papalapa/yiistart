<?php

    namespace papalapa\yiistart\validators;

    use papalapa\yiistart\helpers\FileHelper;
    use yii\validators\FileValidator;
    use yii\validators\Validator;
    use yii\web\UploadedFile;

    /**
     * Class FilePathValidator
     * @package papalapa\yiistart\validators
     */
    class FilePathValidator extends Validator
    {
        /**
         * Webroot full path
         * @var
         */
        public $webroot;
        /**
         * Local path to save
         * @var
         */
        public $path;
        /**
         * Filename pattern
         * @var
         */
        public $pattern;
        /**
         * Rules for file validator
         * @var
         */
        public $fileRules;

        public function init()
        {
            $this->path    = FileHelper::normalizePath(\Yii::getAlias($this->path));
            $this->webroot = FileHelper::normalizePath(\Yii::getAlias($this->webroot));

            parent::init();
        }

        /**
         * @param \yii\base\Model $model
         * @param string          $attribute
         */
        public function validateAttribute($model, $attribute)
        {
            $dirname   = pathinfo($model->$attribute, PATHINFO_DIRNAME);
            $basename  = pathinfo($model->$attribute, PATHINFO_BASENAME);
            $filename  = pathinfo($model->$attribute, PATHINFO_FILENAME);
            $extension = pathinfo($model->$attribute, PATHINFO_EXTENSION);

            if ($this->path <> $dirname) {
                $this->addError($model, $attribute, 'Путь к файлу не соответствует требованиям', $params = []);
            }

            if (!preg_match($this->pattern, $filename)) {
                $this->addError($model, $attribute, 'Название файла не соответствует требованиям', $params = []);
            }

            $file = FileHelper::normalizePath($this->webroot . DIRECTORY_SEPARATOR . $model->$attribute);

            if (!FileHelper::exists($file) || !FileHelper::is_file($file)) {
                $this->addError($model, $attribute, 'Файл не найден', $params = []);
            }

            $model->$attribute = new UploadedFile([
                'name'     => $model->$attribute,
                'tempName' => $file,
                'size'     => FileHelper::size($file),
                'type'     => FileHelper::getMimeTypeByExtension($file),
            ]);

            $fileValidator = Validator::createValidator(FileValidator::className(), $model, [$attribute], $this->fileRules);
            $model->validators->append($fileValidator);
        }
    }
