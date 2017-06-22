<?php

    namespace papalapa\yiistart\modules\pages\models;

    use papalapa\yiistart\models\MultilingualActiveRecord;
    use papalapa\yiistart\modules\menu\models\Menu;
    use papalapa\yiistart\modules\settings\models\Settings;
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
     * @property string             $text
     * @property boolean            $has_text
     * @property string             $context
     * @property string             $has_context
     * @property string             $image
     * @property boolean            $has_image
     * @property integer            $is_active
     * @property integer            $created_by
     * @property integer            $updated_by
     * @property string             $created_at
     * @property string             $updated_at
     * @property PagesTranslation[] $translations
     */
    class Pages extends MultilingualActiveRecord
    {
        const SCENARIO_DEVELOPER = 'developer';

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
                'text'        => 'Текст',
                'has_text'    => 'Страница с текстом',
                'context'     => 'Описание',
                'has_context' => 'Страница с описанием',
                'image'       => 'Изображение',
                'has_image'   => 'Страница с изображением',
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
                    'title', 'description', 'keywords', 'header', 'context', 'text', 'image', 'is_active', 'url', 'has_context', 'has_text', 'has_image',
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
                [['header'], 'required'],
                [['title', 'header'], 'string', 'max' => 256],
                [['description', 'keywords', 'context'], 'string', 'max' => 1024],

                [['url'], WhiteSpaceNormalizerValidator::className()],
                [['url'], 'string', 'max' => 64],
                [['url'], 'match', 'pattern' => '/^(\/[a-z]+(\-[a-z]+)*)+$/'],
                [['url'], 'default', 'value' => null],

                [['has_context'], 'boolean'],
                [['has_context'], 'default', 'value' => 1],
                [['has_context'], 'required'],

                [['has_text'], 'boolean'],
                [['has_text'], 'default', 'value' => 1],
                [['has_text'], 'required'],

                [['has_image'], 'boolean'],
                [['has_image'], 'default', 'value' => 1],
                [['has_image'], 'required'],

                [['is_active'], 'boolean'],
                [['is_active'], 'default', 'value' => 0],

                [['image'], 'string', 'max' => 128, 'enableClientValidation' => false],
                [['image'], FilePathValidator::className()],
            ]);

            if ($rule = Settings::paramOf('page.upload.rule', false)) {
                $rules[] = $rule;
            }

            return $rules;
        }

        /**
         * Remove menu link on deleted page
         * @return bool
         */
        public function afterDelete()
        {
            $siteUrlManager          = clone (\Yii::$app->urlManager);
            $siteUrlManager->baseUrl = '/';
            Menu::deleteAll(['url' => $siteUrlManager->createUrl(['/site/page', 'id' => $this->id])]);

            parent::afterDelete();
        }

        /**
         * Returns page instance or create module page
         * @param integer|string|array $key
         * @return null|\papalapa\yiistart\modules\pages\models\Pages
         */
        public static function pageOf($key)
        {
            $query = static::find();
            if (is_numeric($key)) {
                $query->where(['id' => $key]);
            } elseif (is_string($key)) {
                $query->where(['url' => $key]);
            } elseif (is_array($key)) {
                $url = '/'.implode('/', $key);
                $query->where(['url' => $url]);
            }
            /* @var $model static */
            $model = $query->one();

            if (is_null($model) && isset($url)) {
                $model = new static();
                $model->detachBehavior('BlameableBehavior');
                $model->created_by = 0;
                $model->updated_by = 0;
                $model->header     = $url;
                $model->url        = $url;
                if ($model->validate(['url', 'header']) && $model->save(false)) {
                    \Yii::info(sprintf('Создана отсутствующая запрошенная страница "%s"', $url));
                } else {
                    $firstErrors = $model->firstErrors;
                    \Yii::warning(sprintf('Ошибка при создании запрошенной страницы "%s": %s', $url, reset($firstErrors)));
                    $model = null;
                }
            }

            return $model;
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getTranslations()
        {
            return $this->hasMany(PagesTranslation::className(), ['content_id' => 'id']);
        }
    }
