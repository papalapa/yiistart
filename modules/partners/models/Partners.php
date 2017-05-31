<?php

    namespace papalapa\yiistart\modules\partners\models;

    use papalapa\yiistart\models\MultilingualActiveRecord;
    use papalapa\yiistart\modules\settings\models\Settings;
    use papalapa\yiistart\validators\FilePathValidator;
    use papalapa\yiistart\validators\ReorderValidator;
    use yii\behaviors\BlameableBehavior;
    use yii\behaviors\TimestampBehavior;
    use yii\db\Expression;
    use yii\helpers\ArrayHelper;

    /**
     * This is the model class for table "partners".
     * @property integer               $id
     * @property string                $url
     * @property string                $image
     * @property string                $alt
     * @property string                $title
     * @property integer               $order
     * @property integer               $is_active
     * @property integer               $created_by
     * @property integer               $updated_by
     * @property string                $created_at
     * @property string                $updated_at
     * @property PartnersTranslation[] $translations
     */
    class Partners extends MultilingualActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'partners';
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return $this->localizedAttributes([
                'id'         => 'ID',
                'url'        => 'Ссылка',
                'image'      => 'Изображение',
                'title'      => 'Название',
                'alt'        => 'Тег "alt" изображения',
                'order'      => 'Порядок',
                'is_active'  => 'Активность',
                'created_by' => 'Кем создано',
                'updated_by' => 'Кем изменено',
                'created_at' => 'Дата создания',
                'updated_at' => 'Дата изменения',
            ]);
        }

        /**
         * @return array
         */
        public function behaviors()
        {
            return ArrayHelper::merge(parent::behaviors(), [
                'TimestampBehavior'    => [
                    'class' => TimestampBehavior::className(),
                    'value' => new Expression('NOW()'),
                ],
                'BlameableBehavior'    => [
                    'class' => BlameableBehavior::className(),
                ],
                'MultilingualBehavior' => [
                    'langClassName' => PartnersTranslation::className(),
                    'tableName'     => PartnersTranslation::tableName(),
                    'attributes'    => ['alt', 'title'],
                ],
            ]);
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            $rules = $this->localizedRules([
                [['url'], 'string', 'max' => 256],
                [['url'], 'url'],
                [['title'], 'required'],
                [['alt', 'title'], 'string', 'max' => 128],

                [['order'], 'integer'],
                [['order'], ReorderValidator::className()],
                [['order'], 'required'],

                [['is_active'], 'boolean'],
                [['is_active'], 'default', 'value' => false],

                [['image'], 'required'],
                [['image'], 'string', 'max' => 128, 'enableClientValidation' => false],
                [['image'], FilePathValidator::className()],
            ]);

            if ($rule = Settings::paramOf('partner.upload.rule', false)) {
                $rules[] = $rule;
            }

            return $rules;
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getTranslations()
        {
            return $this->hasMany(PartnersTranslation::className(), ['content_id' => 'id']);
        }
    }
