<?php

    namespace papalapa\yiistart\models;

    use yii\behaviors\TimestampBehavior;
    use yii\db\ActiveRecord;
    use yii\db\BaseActiveRecord;
    use yii\db\Expression;

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
         * TimestampBehavior - is alternative of db triggers to insert new row
         * @return array
         */
        public function behaviors()
        {
            return [
                'time' => [
                    'class'      => TimestampBehavior::className(),
                    'attributes' => [
                        BaseActiveRecord::EVENT_BEFORE_INSERT => ['time_at'],
                    ],
                    'value'      => new Expression('NOW()'),
                ],
                'date' => [
                    'class'      => TimestampBehavior::className(),
                    'attributes' => [
                        BaseActiveRecord::EVENT_BEFORE_INSERT => ['date_at'],
                    ],
                    'value'      => new Expression('CURRENT_DATE()'),
                ],
            ];
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [['model_name', 'model_pk'], 'required'],
                [['model_name'], 'string', 'max' => 64],
                [['model_pk'], 'integer', 'min' => 0],
            ];
        }
    }
