<?php

    namespace papalapa\yiistart\modules\subscribe\models;

    use yii\db\ActiveRecord;

    /**
     * This is the model class for table "subscriptions".
     * @property integer                      $id
     * @property string                       $subject
     * @property string                       $html
     * @property string                       $text
     * @property string                       $start_at
     * @property integer                      $status
     * @property string                       $created_at
     * @property PivotDispatchesSubscribers[] $pivotDispatchesSubscribers
     */
    class Dispatches extends ActiveRecord
    {
        const STATUS_ON  = 1;
        const STATUS_OFF = 0;
        const STATUS_END = -1;

        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'dispatches';
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'id'         => 'ID',
                'subject'    => 'Тема',
                'html'       => 'HTML',
                'text'       => 'Текст',
                'start_at'   => 'Дата начала',
                'status'     => 'Статус',
                'created_at' => 'Дата создания',
            ];
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [['subject', 'html', 'text', 'start_at'], 'required'],
                [['html', 'text'], 'string'],
                [['start_at'], 'date', 'format' => 'yyyy-mm-dd'],
                [['status'], 'integer'],
                [['subject'], 'string', 'max' => 128],
            ];
        }

        /**
         * @return array
         */
        public static function statuses()
        {
            return [
                self::STATUS_ON  => 'активная',
                self::STATUS_OFF => 'не активная',
                self::STATUS_END => 'завершена',
            ];
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getPivotDispatchesSubscribers()
        {
            return $this->hasMany(PivotDispatchesSubscribers::className(), ['dispatch_id' => 'id']);
        }
    }
