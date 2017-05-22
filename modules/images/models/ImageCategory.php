<?php

    namespace papalapa\yiistart\modules\images\models;

    use papalapa\yiistart\validators\WhiteSpaceNormalizerValidator;
    use yii\behaviors\BlameableBehavior;
    use yii\behaviors\TimestampBehavior;
    use yii\db\ActiveRecord;
    use yii\db\Expression;

    /**
     * This is the model class for table "image_category".
     * @property integer  $id
     * @property string   $alias
     * @property string   $name
     * @property integer  $is_visible
     * @property integer  $is_active
     * @property integer  $created_by
     * @property integer  $updated_by
     * @property string   $created_at
     * @property string   $updated_at
     * @property Images[] $images
     */
    class ImageCategory extends ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'image_category';
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'id'         => 'ID',
                'alias'      => 'Альяс',
                'name'       => 'Название',
                'is_visible' => 'Видимость',
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
                [['alias', 'name'], WhiteSpaceNormalizerValidator::className()],
                [['alias', 'name'], 'required'],
                [['alias', 'name'], 'string', 'max' => 64],
                [['alias'], 'match', 'pattern' => '/^[a-z]+(\.[a-z]+)*$/'],
                [['alias'], 'unique'],

                [['is_visible', 'is_active'], 'boolean'],
                [['is_visible', 'is_active'], 'default', 'value' => false],
            ];
        }

        /**
         * @return bool
         */
        public function beforeDelete()
        {
            if (parent::beforeDelete()) {
                if ($this->images) {
                    \Yii::$app->session->setFlash('error', 'В данной категории есть связанные изображения. Удаление невозможно!');

                    return false;
                }

                return true;
            }

            return false;
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getImages()
        {
            return $this->hasMany(Images::className(), ['category_id' => 'id']);
        }
    }
