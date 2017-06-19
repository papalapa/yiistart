<?php

    namespace papalapa\yiistart\models;

    use papalapa\yiistart\helpers\FileHelper;

    /**
     * This is the model class for table "belonging_images".
     * @property integer $id
     * @property string  $content_type
     * @property integer $content_id
     * @property string  $path
     * @property integer $size
     * @property integer $width
     * @property integer $height
     * @property integer $order
     * @property integer $is_outdated
     * @property integer $created_by
     * @property integer $updated_by
     * @property string  $created_at
     * @property string  $updated_at
     */
    class BelongingImages extends BaseBelonging
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'belonging_images';
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return array_merge(parent::attributeLabels(), [
                'path'   => 'Путь',
                'size'   => 'Размер',
                'width'  => 'Ширина',
                'height' => 'Высота',
            ]);
        }

        /**
         * @return array]
         */
        public function scenarios()
        {
            $scenarios                           = parent::scenarios();
            $scenarios[self::SCENARIO_DEFAULT][] = '!path';

            return $scenarios;
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return array_merge(parent::rules(), [
                [['path'], 'required'],
                // [['path'], FilePathValidator::className()], // TODO: check this
                [['path'], 'string', 'max' => 128],
            ]);
        }

        /**
         * @param bool $insert
         * @return bool
         */
        public function beforeSave($insert)
        {
            if (parent::beforeSave($insert)) {
                $path         = \Yii::getAlias("@frontend/web{$this->path}");
                $image        = getimagesize($path);
                $this->width  = !empty($image[0]) ? (int)$image[0] : null;
                $this->height = !empty($image[1]) ? (int)$image[1] : null;
                $this->size   = FileHelper::size($path);

                return true;
            }

            return false;
        }
    }
