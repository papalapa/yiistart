<?php

    namespace papalapa\yiistart\modules\menu\models;

    use papalapa\yiistart\models\MultilingualActiveRecord;
    use papalapa\yiistart\modules\pages\models\Pages;
    use papalapa\yiistart\validators\WhiteSpaceNormalizerValidator;
    use yii\behaviors\BlameableBehavior;
    use yii\behaviors\TimestampBehavior;
    use yii\db\Expression;
    use yii\helpers\ArrayHelper;

    /**
     * This is the model class for table "menu".
     * @property integer           $id
     * @property string            $position
     * @property string            $url
     * @property string            $title
     * @property integer           $order
     * @property boolean           $is_active
     * @property integer           $created_by
     * @property integer           $updated_by
     * @property string            $created_at
     * @property string            $updated_at
     * @property MenuTranslation[] $menuTranslations
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
                'position'   => 'Расположение',
                'url'        => 'Ссылка',
                'title'      => 'Название',
                'order'      => 'Порядковый номер',
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
                [['url'], WhiteSpaceNormalizerValidator::className()],
                [['url'], 'required'],
                [['url'], 'string', 'max' => 64],
                [
                    ['url'],
                    'in',
                    'range' => function () {
                        $siteUrlManager          = clone (\Yii::$app->urlManager);
                        $siteUrlManager->baseUrl = '/';
                        $urls                    = array_map(function ($element) use ($siteUrlManager) /* @var $element Pages */ {
                            return $siteUrlManager->createUrl($element->url ? [$element->url] : ['/site/page', 'id' => $element->id]);
                        }, Pages::find()->select(['id', 'url'])->all());

                        return $urls;
                    },
                    'when'  => function () {
                        return Pages::find()->count();
                    },
                ],

                [['position'], 'string', 'max' => 32],
                [['position'], 'in', 'range' => array_keys(self::positions())],
                [['position'], 'default', 'value' => self::POSITION_MAIN],

                [['title'], WhiteSpaceNormalizerValidator::className()],
                [['title'], 'required'],
                [['title'], 'string', 'max' => 32],

                [['order'], 'integer'],
                [
                    ['order'], 'unique',
                    'when' => function ($model) /* @var $model $this */ {
                        if (!empty($model->order)) {
                            self::updateAllCounters(['order' => 1], ['>=', 'order', $model->order]);

                            return true;
                        }

                        return false;
                    },
                ],
                [
                    ['order'],
                    'default',
                    'value' => function ($model) /* @var $model self */ {
                        return self::find()->max('order') + 1;
                    },
                ],
                [['order'], 'required'],

                [['is_active'], 'boolean'],
                [['is_active'], 'default', 'value' => false],
            ]);
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

            $positions = ArrayHelper::getValue(\Yii::$app->params, 'menu.positions', $initialPositions);

            return $positions;
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getMenuTranslations()
        {
            return $this->hasMany(MenuTranslation::className(), ['content_id' => 'id']);
        }
    }
