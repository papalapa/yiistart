<?php

    namespace papalapa\yiistart\modules\subscribe\models;

    use yii\db\ActiveRecord;

    /**
     * This is the model class for table "pivot_subscriptions_subscribers".
     * @property integer     $id
     * @property integer     $subscription_id
     * @property integer     $subscriber_id
     * @property integer     $status
     * @property Subscribers $subscriber
     * @property Dispatches  $dispatch
     */
    class PivotDispatchesSubscribers extends ActiveRecord
    {
        const STATUS_SEND = 1;
        const STATUS_WAIT = 0;

        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'pivot_dispatches_subscribers';
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'id'            => 'ID',
                'dispatch_id'   => 'Пользователь',
                'subscriber_id' => 'Рассылка',
                'status'        => 'Статус',
            ];
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [['dispatch_id', 'subscriber_id'], 'required'],
                [['dispatch_id', 'subscriber_id', 'status'], 'integer'],
                [
                    ['subscriber_id'],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Subscribers::className(),
                    'targetAttribute' => ['subscriber_id' => 'id'],
                ],
                [
                    ['dispatch_id'],
                    'exist',
                    'skipOnError'     => true,
                    'targetClass'     => Dispatches::className(),
                    'targetAttribute' => ['dispatch_id' => 'id'],
                ],
            ];
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getSubscriber()
        {
            return $this->hasOne(Subscribers::className(), ['id' => 'subscriber_id']);
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getDispatch()
        {
            return $this->hasOne(Dispatches::className(), ['id' => 'dispatch_id']);
        }
    }
