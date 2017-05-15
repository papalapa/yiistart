<?php

    namespace papalapa\yiistart\modules\subscribe\models;

    use yii\behaviors\TimestampBehavior;
    use yii\db\ActiveRecord;
    use yii\db\Expression;

    /**
     * This is the model class for table "subscribers".
     * @property integer                      $id
     * @property string                       $email
     * @property integer                      $status
     * @property string                       $created_at
     * @property PivotDispatchesSubscribers[] $pivotDispatchesSubscribers
     */
    class Subscribers extends ActiveRecord
    {
        const STATUS_ON  = 1;
        const STATUS_OFF = 0;

        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'subscribers';
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'id'         => 'ID',
                'email'      => 'Email',
                'status'     => 'Статус подписки',
                'created_at' => 'Дата подписки',
            ];
        }

        /**
         * @return array
         */
        public function behaviors()
        {
            return [
                'TimestampBehavior' => [
                    'class'      => TimestampBehavior::className(),
                    'value'      => new Expression('NOW()'),
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                    ],
                ],
            ];
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [['email'], 'trim'],
                [['email'], 'required'],
                [['email'], 'string', 'max' => 128],
                [['email'], 'email'],
                [['email'], 'unique'],
                [['status'], 'boolean'],
                [['status'], 'default', 'value' => true],
            ];
        }

        public static function statuses()
        {
            return [
                self::STATUS_ON  => 'подписан',
                self::STATUS_OFF => 'не пописан',
            ];
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getPivotDispatchesSubscribers()
        {
            return $this->hasMany(PivotDispatchesSubscribers::className(), ['subscriber_id' => 'id']);
        }
    }
