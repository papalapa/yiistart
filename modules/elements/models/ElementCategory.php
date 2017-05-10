<?php

    namespace papalapa\yiistart\modules\elements\models;

    use yii\behaviors\BlameableBehavior;
    use yii\behaviors\TimestampBehavior;
    use yii\db\ActiveRecord;
    use yii\db\Expression;

    /**
     * This is the model class for table "element_category".
     * @property integer    $id
     * @property string     $name
     * @property integer    $created_by
     * @property integer    $updated_by
     * @property string     $created_at
     * @property string     $updated_at
     * @property Elements[] $elements
     */
    class ElementCategory extends ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'element_category';
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'id'         => 'ID',
                'name'       => 'Название',
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
                [['name'], 'required'],
                [['name'], 'string', 'max' => 64],
                [['name'], 'unique'],
            ];
        }

        /**
         * @return bool
         */
        public function beforeDelete()
        {
            if ($this->elements) {
                \Yii::$app->session->setFlash('error', 'У этой категории есть связанные элементы. Удаление невозможно.');
                return false;
            }

            return parent::beforeDelete();
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getElements()
        {
            return $this->hasMany(Elements::className(), ['category_id' => 'id']);
        }
    }
