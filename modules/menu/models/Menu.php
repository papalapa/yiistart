<?php

    namespace papalapa\yiistart\modules\menu\models;

    use papalapa\yiistart\models\MultilingualActiveRecord;
    use papalapa\yiistart\modules\pages\models\Pages;
    use papalapa\yiistart\modules\settings\models\Settings;
    use papalapa\yiistart\validators\ReorderValidator;
    use papalapa\yiistart\validators\WhiteSpaceNormalizerValidator;
    use yii\behaviors\BlameableBehavior;
    use yii\behaviors\TimestampBehavior;
    use yii\db\Expression;
    use yii\helpers\ArrayHelper;

    /**
     * This is the model class for table "menu".
     * @property integer           $id
     * @property integer           $parent
     * @property string            $position
     * @property string            $url
     * @property string            $title
     * @property integer           $order
     * @property boolean           $is_static
     * @property boolean           $is_active
     * @property integer           $created_by
     * @property integer           $updated_by
     * @property string            $created_at
     * @property string            $updated_at
     * @property MenuTranslation[] $translations
     */
    class Menu extends MultilingualActiveRecord
    {
        /**
         * Menu links positions
         */
        const POSITION_TOP    = 'top';
        const POSITION_MAIN   = 'main';
        const POSITION_BOTTOM = 'bottom';

        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'menu';
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return $this->localizedAttributes([
                'id'         => 'ID',
                'parent'     => 'Родительский пункт',
                'position'   => 'Расположение',
                'url'        => 'Ссылка',
                'title'      => 'Название',
                'order'      => 'Порядковый номер',
                'is_static'  => 'Статичная ссылка',
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
                    'langClassName' => MenuTranslation::className(),
                    'tableName'     => MenuTranslation::tableName(),
                    'attributes'    => ['title'],
                ],
            ]);
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return $this->localizedRules([
                [['parent'], 'integer'],
                [
                    ['parent'], 'exist',
                    'targetAttribute' => 'id',
                    'filter'          => function ($q) /* @var $q \yii\db\ActiveQuery */ {
                        return $q->where(['OR', ['parent' => null], ['parent' => '']])
                                 ->andFilterWhere(['<>', 'id', $this->id]);
                    },
                ],

                [['url'], WhiteSpaceNormalizerValidator::className()],
                [['url'], 'required'],
                [['url'], 'string', 'max' => 1024],
                [
                    ['url'], 'in',
                    'range'                  => function () {
                        $siteUrlManager          = clone (\Yii::$app->urlManager);
                        $siteUrlManager->baseUrl = '/';
                        $urls                    = array_map(function ($element) use ($siteUrlManager) /* @var $element Pages */ {
                            return $siteUrlManager->createUrl($element->url ? [$element->url] : ['/site/page', 'id' => $element->id]);
                        }, Pages::find()->select(['id', 'url'])->all());

                        return $urls;
                    },
                    'enableClientValidation' => false,
                    'when'                   => function ($model) /* @var $model self */ {
                        return !$model->is_static && Pages::find()->count();
                    },
                ],

                [['position'], 'string', 'max' => 32],
                [['position'], 'default', 'value' => self::POSITION_MAIN],
                [['position'], 'in', 'range' => array_keys(self::positions())],

                [['title'], WhiteSpaceNormalizerValidator::className()],
                [['title'], 'required'],
                [['title'], 'string', 'max' => 64],

                [['order'], 'integer'],
                [['order'], ReorderValidator::className(), 'extraFields' => ['position', 'parent']],
                [['order'], 'required'],

                [['is_active'], 'boolean'],
                [['is_active'], 'default', 'value' => false],

                [['is_static'], 'boolean'],
                [['is_static'], 'default', 'value' => false],
            ]);
        }

        /**
         * @param bool $insert
         * @return bool
         */
        public function beforeSave($insert)
        {
            if (parent::beforeSave($insert)) {
                if (!empty($this->parent) && $parent = self::findOne($this->parent)) {
                    $this->position = $parent->position;
                }

                return true;
            }

            return false;
        }

        /**
         * Top menu elements only
         * @return array|\yii\db\ActiveRecord[]
         */
        public static function roots()
        {
            return array_map(function ($element) {
                $element->title = ArrayHelper::getValue(static::positions(), $element->position).' / '.$element->title;

                return $element;
            }, self::find()->where(['OR', ['parent' => null], ['parent' => '']])
                   ->orderBy(['position' => SORT_ASC, 'title' => SORT_ASC])->indexBy('id')->all());
        }

        /**
         * @return array
         */
        public static function positions()
        {
            $initialPositions = [
                self::POSITION_TOP    => 'Верхнее меню',
                self::POSITION_MAIN   => 'Основное меню',
                self::POSITION_BOTTOM => 'Нижнее меню',
            ];

            $positions = Settings::paramOf('menu.positions', $initialPositions);

            return $positions;
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getTranslations()
        {
            return $this->hasMany(MenuTranslation::className(), ['content_id' => 'id']);
        }
    }
