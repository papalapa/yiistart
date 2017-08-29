<?php

    namespace papalapa\yiistart\modules\social\models;

    use papalapa\yiistart\modules\settings\models\Settings;
    use papalapa\yiistart\validators\FilePathValidator;
    use papalapa\yiistart\validators\ReorderValidator;
    use yii\behaviors\BlameableBehavior;
    use yii\behaviors\TimestampBehavior;
    use yii\db\ActiveRecord;
    use yii\db\Expression;

    /**
     * This is the model class for table "social".
     *
     * @property integer $id
     * @property string  $name
     * @property string  $position
     * @property string  $url
     * @property string  $image
     * @property string  $title
     * @property string  $alt
     * @property integer $order
     * @property integer $is_active
     * @property integer $created_by
     * @property integer $updated_by
     * @property string  $created_at
     * @property string  $updated_at
     */
    class Social extends ActiveRecord
    {
        const POSITION_DEFAULT = 'default';

        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'social';
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'id'         => 'ID',
                'name'       => 'Название',
                'position'   => 'Расположение',
                'url'        => 'Ссылка',
                'image'      => 'Изображение',
                'title'      => 'IMG:title',
                'alt'        => 'IMG:alt',
                'order'      => 'Порядок',
                'is_active'  => 'Активность',
                'created_by' => 'Кем создано',
                'updated_by' => 'Кем изменено',
                'created_at' => 'Дата создания',
                'updated_at' => 'Дата изменения',
            ];
        }

        /**
         * @return array
         */
        public function behaviors()
        {
            return [
                'TimestampBehavior' => [
                    'class' => TimestampBehavior::className(),
                    'value' => new Expression('NOW()'),
                ],
                'BlameableBehavior' => [
                    'class' => BlameableBehavior::className(),
                ],
            ];
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [['name', 'position', 'url', 'title', 'alt'], 'string', 'max' => 128],
                [['position'], 'string', 'max' => 128],
                [['position'], 'match', 'pattern' => '/^[a-z]+(\.[a-z]+[0-9]*)*$/'],
                [['position'], 'default', 'value' => self::POSITION_DEFAULT],
                [['position'], 'in', 'range' => array_keys(self::positions())],

                [['order'], 'integer'],
                [['order'], ReorderValidator::className(), 'extraFields' => ['position']],
                [['order'], 'required'],

                [['is_active'], 'boolean'],
                [['is_active'], 'default', 'value' => false],

                [['image'], 'string', 'max' => 128, 'enableClientValidation' => false],
                [['image'], FilePathValidator::className()],
            ];
        }

        /**
         * @return array
         */
        public static function positions()
        {
            return Settings::paramOf('social.positions', [self::POSITION_DEFAULT => 'По умолчанию']);
        }
    }
