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
    use yii\web\Controller;

    /**
     * This is the model class for table "menu".
     * @property integer           $id
     * @property integer           $parent_id
     * @property string            $position
     * @property string            $url
     * @property string            $name
     * @property integer           $order
     * @property integer           $level
     * @property boolean           $is_static
     * @property boolean           $is_active
     * @property integer           $created_by
     * @property integer           $updated_by
     * @property string            $created_at
     * @property string            $updated_at
     * @property MenuTranslation[] $translations
     * @property Menu              $parent
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
                'parent_id'  => 'Родительский пункт',
                'position'   => 'Расположение',
                'url'        => 'Ссылка',
                'name'       => 'Название',
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
                    'attributes'    => ['name'],
                ],
            ]);
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return $this->localizedRules([
                [['parent_id'], 'integer'],
                [
                    ['parent_id'], 'exist',
                    'targetAttribute' => 'id',
                    'filter'          => function ($q) /* @var $q \yii\db\ActiveQuery */ {
                        return $q->where(['OR', ['parent_id' => null], ['parent_id' => '']])
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

                [['name'], WhiteSpaceNormalizerValidator::className()],
                [['name'], 'required'],
                [['name'], 'string', 'max' => 64],

                [['order'], 'integer'],
                [['order'], ReorderValidator::className(), 'extraFields' => ['position', 'parent_id']],
                [['order'], 'required'],

                [['is_active'], 'boolean'],
                [['is_active'], 'default', 'value' => false],

                [['is_static'], 'boolean'],
                [['is_static'], 'default', 'value' => false],

                [['!level'], 'default', 'value' => 0],
            ]);
        }

        /**
         * @param bool $insert
         * @return bool
         */
        public function beforeSave($insert)
        {
            if (parent::beforeSave($insert)) {
                if (!empty($this->parent_id) && $parent = self::findOne($this->parent_id)) {
                    $this->position = $parent->position;
                    $this->level    = $parent->level + 1;
                }
                if ($this->level > $maxLevel = self::maxLevelOf($this->position)) {
                    $this->addError('parent_id', vsprintf('Превышен максимальный уровень вложенности для меню «%s»', [
                        ArrayHelper::getValue(self::positions(), $this->position),
                    ]));

                    return false;
                }

                return true;
            }

            return false;
        }

        /**
         * @return bool
         */
        public function beforeDelete()
        {
            if (parent::beforeDelete()) {
                if (Menu::find()->where(['parent_id' => $this->id])->exists()) {
                    \Yii::$app->session->addFlash('error', 'Есть вложенные пункты меню');

                    return false;
                }

                return true;
            }

            return false;
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
         * @param $position
         * @return mixed
         */
        public static function maxLevelOf($position)
        {
            $depths = Settings::paramOf('menu.depth', [$position => Settings::paramOf('menu.level.max', 0)]);

            return ArrayHelper::getValue($depths, $position);
        }

        /**
         * @return MenuQuery
         */
        public static function find()
        {
            return new MenuQuery(get_called_class());
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getTranslations()
        {
            return $this->hasMany(MenuTranslation::className(), ['content_id' => 'id']);
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getParent()
        {
            return $this->hasOne(self::className(), ['id' => 'parent_id']);
        }

        /**
         * Checks that requested url and menu item are identical
         * @param string $pageController
         * @param string $pageAction
         * @return bool
         */
        public function isRequested($pageController = 'site', $pageAction = 'page')
        {
            $requestedControllerAction = parse_url($this->url, PHP_URL_PATH);
            list($controller, $actionID) = \Yii::$app->createController($requestedControllerAction);

            if ($controller instanceof Controller) {
                $actionID = $actionID ? : $controller->defaultAction;

                parse_str(parse_url($this->url, PHP_URL_QUERY), $queryParams);

                if (\Yii::$app->controller->id == $controller->id && \Yii::$app->controller->action->id == $actionID) {
                    if (\Yii::$app->controller->id == $pageController && \Yii::$app->controller->action->id == $pageAction) {
                        return \Yii::$app->request->getQueryParam('id') == ArrayHelper::getValue($queryParams, 'id');
                    }

                    return true;
                }
            }

            return false;
        }
    }
