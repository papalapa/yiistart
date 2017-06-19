<?php

    namespace papalapa\yiistart\models;

    use papalapa\yiistart\validators\WhiteSpaceNormalizerValidator;

    /**
     * This is the model class for table "belonging_tags".
     * @property integer $id
     * @property string  $content_type
     * @property integer $content_id
     * @property string  $tag
     * @property integer $order
     * @property integer $is_outdated
     * @property integer $created_by
     * @property integer $updated_by
     * @property string  $created_at
     * @property string  $updated_at
     */
    class BelongingTags extends BaseBelonging
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'belonging_tags';
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return array_merge(parent::attributeLabels(), [
                'tag' => 'Тег',
            ]);
        }

        /**
         * @return array]
         */
        public function scenarios()
        {
            $scenarios                           = parent::scenarios();
            $scenarios[self::SCENARIO_DEFAULT][] = '!tag';

            return $scenarios;
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return array_merge(parent::rules(), [
                [['tag'], WhiteSpaceNormalizerValidator::className()],
                [['tag'], 'required'],
                [['tag'], 'string', 'max' => 128],
            ]);
        }
    }
