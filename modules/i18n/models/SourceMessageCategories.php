<?php

    namespace papalapa\yiistart\modules\i18n\models;

    use yii\db\ActiveRecord;

    /**
     * This is the model class for table "source_message_categories".
     * @property string $category
     * @property string $translate
     */
    class SourceMessageCategories extends ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'source_message_categories';
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'category'  => 'Категория',
                'translate' => 'Расшифровка',
            ];
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [['category'], 'required'],
                [['category', 'translate'], 'string', 'max' => 255],
            ];
        }
    }
