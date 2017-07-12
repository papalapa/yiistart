<?php

    namespace papalapa\yiistart\modules\histories\models;

    use papalapa\yiistart\models\MultilingualActiveRecord;
    use papalapa\yiistart\modules\settings\models\Settings;
    use papalapa\yiistart\validators\FilePathValidator;
    use yii\behaviors\BlameableBehavior;
    use yii\behaviors\TimestampBehavior;
    use yii\db\Expression;
    use yii\helpers\ArrayHelper;

    /**
     * This is the model class for table "histories".
     * @property integer                $id
     * @property string                 $date
     * @property string                 $title
     * @property string                 $text
     * @property string                 $image
     * @property integer                $is_active
     * @property integer                $created_by
     * @property integer                $updated_by
     * @property string                 $created_at
     * @property string                 $updated_at
     * @property HistoriesTranslation[] $translations
     */
    class Histories extends MultilingualActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'histories';
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return $this->localizedAttributes([
                'id'         => 'ID',
                'date'       => 'Дата',
                'title'      => 'Заголовок',
                'text'       => 'Текст',
                'image'      => 'Изображение',
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
                    'langClassName' => HistoriesTranslation::className(),
                    'tableName'     => HistoriesTranslation::tableName(),
                    'attributes'    => ['title', 'text'],
                ],
            ]);
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            $rules = $this->localizedRules([
                [['date'], 'required'],
                [['date'], 'date', 'format' => 'yyyy-mm-dd'],
                [['date'], 'unique'],

                [['title'], 'string', 'max' => 256],

                [['text'], 'string'],

                [['is_active'], 'boolean'],
                [['is_active'], 'default', 'value' => false],

                [['image'], 'string', 'max' => 128, 'enableClientValidation' => false],
                [['image'], FilePathValidator::className()],
            ]);

            if ($rule = Settings::paramOf('histories.upload.rule', false)) {
                $rules[] = $rule;
            }

            return $rules;
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getTranslations()
        {
            return $this->hasMany(HistoriesTranslation::className(), ['content_id' => 'id']);
        }
    }
