<?php

    namespace papalapa\yiistart\modules\pages\models;

    use papalapa\yiistart\models\MultilingualActiveRecord;
    use papalapa\yiistart\models\User;
    use papalapa\yiistart\modules\menu\models\Menu;
    use papalapa\yiistart\validators\FilePathValidator;
    use papalapa\yiistart\validators\WhiteSpaceNormalizerValidator;
    use yii\behaviors\BlameableBehavior;
    use yii\behaviors\TimestampBehavior;
    use yii\db\Expression;
    use yii\helpers\ArrayHelper;

    /**
     * This is the model class for table "pages".
     * @property integer            $id
     * @property string             $url
     * @property string             $title
     * @property string             $description
     * @property string             $keywords
     * @property string             $header
     * @property string             $context
     * @property string             $text
     * @property string             $image
     * @property boolean            $contextable
     * @property boolean            $imagable
     * @property integer            $is_active
     * @property integer            $created_by
     * @property integer            $updated_by
     * @property string             $created_at
     * @property string             $updated_at
     * @property PagesTranslation[] $translations
     */
    class Pages extends MultilingualActiveRecord
    {
        /**
         * Developer`s scenario
         */
        const SCENARIO_DEVELOPER = 'developer';
        /**
         * @var
         */
        public $uploadController = 'upload';
        /**
         * @var
         */
        public $uploadRule;

        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'pages';
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return $this->localizedAttributes([
                'id'          => 'ID',
                'url'         => 'Статическая ссылка',
                'title'       => 'Мета-тег Title',
                'description' => 'Мета-тег Description',
                'keywords'    => 'Мета-тег Keywords',
                'header'      => 'Заголовок',
                'context'     => 'Описание',
                'text'        => 'Текст',
                'image'       => 'Изображение',
                'contextable' => 'Контекстная страница',
                'imagable'    => 'Страница с изображением',
                'is_active'   => 'Активность',
                'created_by'  => 'Кем создано',
                'updated_by'  => 'Кем изменено',
                'created_at'  => 'Дата создания',
                'updated_at'  => 'Дата изменения',
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
                    'langClassName' => PagesTranslation::className(),
                    'tableName'     => PagesTranslation::tableName(),
                    'attributes'    => ['title', 'description', 'keywords', 'header', 'context', 'text'],
                ],
            ]);
        }

        /**
         * @return array
         */
        public function scenarios()
        {
            return $this->localizedScenarios([
                self::SCENARIO_DEFAULT   => ['title', 'description', 'keywords', 'header', 'context', 'text', 'image', 'is_active'],
                self::SCENARIO_DEVELOPER => [
                    'title', 'description', 'keywords', 'header', 'context', 'text', 'image', 'is_active', 'url', 'contextable', 'imagable',
                ],
            ]);
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            $rules = $this->localizedRules([
                [['text', 'title', 'header', 'context', 'description', 'keywords'], WhiteSpaceNormalizerValidator::className()],
                [['text'], 'string'],
                [['title', 'header'], 'string', 'max' => 256],
                [['description', 'keywords', 'context'], 'string', 'max' => 1024],

                [['url'], WhiteSpaceNormalizerValidator::className()],
                [['url'], 'string', 'max' => 64],
                [['url'], 'match', 'pattern' => '/^(\/[a-z]+(\-[a-z]+)*)+$/'],
                [['url'], 'default', 'value' => null],

                [['contextable'], 'boolean'],
                [['contextable'], 'required',],
                [['contextable'], 'default', 'value' => 1],

                [['imagable'], 'boolean'],
                [['imagable'], 'required'],
                [['imagable'], 'default', 'value' => 1],

                [['is_active'], 'boolean'],
                [['is_active'], 'default', 'value' => 0],

                [['image'], 'string', 'max' => 128, 'enableClientValidation' => false],
                [['image'], FilePathValidator::className(), 'uploadController' => $this->uploadController],
            ]);

            if ($this->uploadRule) {
                $rules[] = $this->uploadRule;
            }

            return $rules;
        }

        /**
         * @return bool
         */
        public function beforeDelete()
        {
            if ($this->url) {
                \Yii::$app->session->setFlash('error', 'Эта страница является модульной, удалить ее нельзя.');

                return false;
            }

            $siteUrlManager          = clone (\Yii::$app->urlManager);
            $siteUrlManager->baseUrl = '/';
            $url                     = $siteUrlManager->createUrl(['/site/page', 'id' => $this->id]);

            if (Menu::find()->where(['url' => $url])->count()) {
                \Yii::$app->session->setFlash('error', 'Перед удалением этой страницы необходимо удалить ссылку на неё из модуля "Меню".');

                return false;
            }

            return parent::beforeDelete();
        }

        /**
         * @param array $data
         * @param null  $formName
         * @return bool
         */
        public function load($data, $formName = null)
        {
            if (User::identity()->role == User::ROLE_DEVELOPER) {
                $this->scenario = self::SCENARIO_DEVELOPER;
            }

            return parent::load($data, $formName);
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getTranslations()
        {
            return $this->hasMany(PagesTranslation::className(), ['content_id' => 'id']);
        }
    }
