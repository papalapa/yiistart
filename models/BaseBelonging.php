<?php

    namespace papalapa\yiistart\models;

    use papalapa\yiistart\validators\ReorderValidator;
    use yii\base\InvalidCallException;
    use yii\behaviors\BlameableBehavior;
    use yii\behaviors\TimestampBehavior;
    use yii\db\ActiveRecord;
    use yii\db\Expression;

    /**
     * Class BaseBelonging
     * @package papalapa\yiistart\models
     */
    abstract class BaseBelonging extends ActiveRecord
    {
        /**
         * @return string
         */
        public static function tableName()
        {
            throw new InvalidCallException('You must define your own method '.__METHOD__.' in your AR '.get_called_class());
        }

        /**
         * @return array
         */
        public function attributeLabels()
        {
            return [
                'id'           => 'ID',
                'content_type' => 'Тип контента',
                'content_id'   => 'ID контента',
                'order'        => 'Порядок',
                'is_outdated'  => 'Метка на удаление',
                'created_by'   => 'Создано',
                'updated_by'   => 'Обновлено',
                'created_at'   => 'Дата создания',
                'updated_at'   => 'Дата обновления',
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
         * @return array]
         */
        public function scenarios()
        {
            return [
                self::SCENARIO_DEFAULT => ['!content_type', '!content_id', '!order', '!outdated'],
            ];
        }

        /**
         * @return array
         */
        public function rules()
        {
            return [
                [['id'], 'integer', 'min' => 0],

                [['content_type'], 'string'],

                [['content_id'], 'required'],
                [['content_id'], 'integer', 'min' => 0],

                [['order'], 'integer'],
                [['order'], ReorderValidator::className(), 'extraFields' => ['content_type']],
                [['order'], 'required'],

                [['is_outdated'], 'boolean'],
                [['is_outdated'], 'default', 'value' => false],
            ];
        }
    }
