<?php

    namespace papalapa\yiistart\modules\menu\models;

    use papalapa\yiistart\models\MultilingualActiveRecord;
    use papalapa\yiistart\modules\pages\models\Pages;
    use papalapa\yiistart\validators\WhiteSpaceNormalizerValidator;
    use yii\behaviors\BlameableBehavior;
    use yii\behaviors\TimestampBehavior;
    use yii\db\ActiveQuery;
    use yii\db\Expression;
    use yii\helpers\ArrayHelper;

    /**
     * This is the model class for table "menu".
     * @property integer           $id
     * @property string            $position
     * @property string            $url
     * @property string            $title
     * @property boolean           $is_active
     * @property integer           $sort
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
        const POSITION_TOP    = 'Верхнее меню';
        const POSITION_MAIN   = 'Основное меню';
        const POSITION_BOTTOM = 'Нижнее меню';

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
                'sort'       => 'Порядковый номер',
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
                /**
                 * Development scenario only
                 */
                [['url'], 'required'],
                [['url'], WhiteSpaceNormalizerValidator::className()],
                [['url'], 'string', 'max' => 64],
                [
                    ['url'],
                    'in',
                    'range' => function () {
                        $siteUrlManager          = clone (\Yii::$app->urlManager);
                        $siteUrlManager->baseUrl = '/';
                        $pageUrls                = array_map(function ($element) use ($siteUrlManager) /* @var $element Pages */ {
                            return $siteUrlManager->createUrl($element->url ? [$element->url] : ['/site/page', 'id' => $element->id]);
                        }, Pages::find()->select(['id', 'url'])->all());

                        return $pageUrls;
                    },
                    'when'  => function ($model) {
                        return Pages::find()->count();
                    },
                ],

                [['position'], 'string', 'max' => 32],
                [['position'], 'in', 'range' => self::positions()],
                [['position'], 'default', 'value' => self::POSITION_MAIN],

                [['title'], 'required'],
                [['title'], WhiteSpaceNormalizerValidator::className()],
                [['title'], 'string', 'max' => 16],

                [['sort'], 'integer'],
                [
                    ['sort'],
                    'default',
                    'value' => function ($model) /* @var $model self */ {
                        return Menu::find()->max('sort') + 1;
                    },
                ],
                [['sort'], 'required'],
                [['sort'], 'unique'],

                [['is_active'], 'boolean'],
                [['is_active'], 'default', 'value' => false],
            ]);
        }

        /**
         * @return array
         */
        public static function positions()
        {
            $positions = [self::POSITION_TOP, self::POSITION_MAIN, self::POSITION_BOTTOM];

            $availablePositions = ArrayHelper::getValue(\Yii::$app->params, 'menu.positions', $positions);
            if ($availablePositions <> array_values($availablePositions)) {
                $availablePositions = array_keys($availablePositions);
            }

            $combinedPositions = array_intersect($positions, $availablePositions);

            return array_combine($combinedPositions, $combinedPositions);
        }

        /**
         * @return ActiveQuery|MenuQuery
         */
        public static function find()
        {
            return new MenuQuery(get_called_class());
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getMenuTranslations()
        {
            return $this->hasMany(MenuTranslation::className(), ['content_id' => 'id']);
        }
    }
