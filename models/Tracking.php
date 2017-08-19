<?php

    namespace papalapa\yiistart\models;

    use yii\db\ActiveRecord;

    /**
     * This is the model class for table "tracking".
     *
     * @property string $model_name
     * @property string $model_pk
     * @property string $time_at
     * @property string $date_at
     */
    class Tracking extends ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'tracking';
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'model_name' => 'Model Name',
                'model_pk'   => 'Model Pk',
                'time_at'    => 'Time At',
                'date_at'    => 'Date At',
            ];
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [['model_name', 'model_pk'], 'required'],
                [['model_name', 'model_pk'], 'string', 'max' => 64],
            ];
        }
    }
