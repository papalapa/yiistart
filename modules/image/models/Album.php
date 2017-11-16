<?php

    namespace papalapa\yiistart\modules\image\models;

    use yii\behaviors\BlameableBehavior;
    use yii\behaviors\TimestampBehavior;
    use yii\db\ActiveRecord;
    use yii\helpers\Html;

    /**
     * This is the model class for table "album".
     *
     * @property integer $id
     * @property string  $alias
     * @property string  $name
     * @property integer $scale
     * @property string  $template
     * @property integer $has_name
     * @property integer $has_alt
     * @property integer $has_title
     * @property integer $has_text
     * @property integer $has_caption
     * @property integer $has_src
     * @property integer $has_cssclass
     * @property integer $has_twin
     * @property integer $has_twin_cssclass
     * @property integer $has_link
     * @property integer $has_link_cssclass
     * @property string  $validator_controller
     * @property string  $validator_extensions
     * @property integer $validator_min_size
     * @property integer $validator_max_size
     * @property string  $description
     * @property integer $is_multilingual_images
     * @property integer $is_visible
     * @property integer $is_active
     * @property integer $created_by
     * @property integer $updated_by
     * @property string  $created_at
     * @property string  $updated_at
     *
     * @property Image[] $images
     */
    class Album extends ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'app_album';
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'id'                     => 'ID',
                'alias'                  => 'Альяс',
                'name'                   => 'Название',
                'scale'                  => 'Размер альбома',
                'template'               => 'Шаблон вывода',
                'has_name'               => 'Название',
                'has_text'               => 'Текст',
                'has_caption'            => 'Описание',
                'has_alt'                => 'Атрибут «alt»',
                'has_title'              => 'Атрибут «title»',
                'has_src'                => 'Изображение',
                'has_cssclass'           => 'CSS класс изображения',
                'has_twin'               => 'Изображение-связка',
                'has_twin_cssclass'      => 'CSS класс изображения-связки',
                'has_link'               => 'Ссылка',
                'has_link_cssclass'      => 'CSS класс ссылки',
                'validator_controller'   => 'Контроллер валидатора',
                'validator_extensions'   => 'Доступные расширения файлов',
                'validator_min_size'     => 'Минимальный размер файла',
                'validator_max_size'     => 'Максимальный размер файла',
                'description'            => 'Описание альбома',
                'is_multilingual_images' => 'Мультиязычные изображения',
                'is_visible'             => 'Видимость',
                'is_active'              => 'Активность',
                'created_by'             => 'Кем создано',
                'updated_by'             => 'Кем изменено',
                'created_at'             => 'Дата создания',
                'updated_at'             => 'Дата изменения',
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
                    'value' => date('Y-m-d H:i:s'),
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
                [['alias'], 'required'],
                [['alias'], 'string', 'max' => 32],
                [['alias'], 'match', 'pattern' => '/^[a-z]+(\.[a-z]+)*$/'],
                [['alias'], 'unique'],

                [['name'], 'required'],
                [['name'], 'string', 'max' => 64],

                [['scale', 'validator_min_size', 'validator_max_size'], 'integer', 'min' => 0],
                [
                    ['validator_max_size'], 'compare',
                    'operator'         => '>=',
                    'compareAttribute' => 'validator_min_size',
                    'when'             => function ($model) /* @var $model $this */ {
                        return $model->validator_min_size;
                    },
                    'whenClient'       => sprintf("$('#%s').val()", Html::getInputId($this, 'validator_min_size')),
                ],

                [['has_name', 'has_alt', 'has_title', 'has_text', 'has_caption', 'has_src', 'has_cssclass', 'has_twin', 'has_twin_cssclass', 'has_link', 'has_link_cssclass', 'is_multilingual_images', 'is_visible', 'is_active'], 'boolean'],

                [['has_name', 'has_alt', 'has_title', 'has_text', 'has_caption', 'has_src', 'has_cssclass', 'has_twin', 'has_twin_cssclass', 'has_link', 'has_link_cssclass', 'is_multilingual_images', 'is_visible', 'is_active'], 'default', 'value' => false],

                [['validator_controller', 'validator_extensions'], 'string', 'max' => 32],

                [['description'], 'string'],

                [['template'], 'string', 'max' => 1024],
            ];
        }

        /**
         * Returns all images of album by alias
         * @param $alias
         * @return array|Image[]
         */
        public static function itemsOf($alias)
        {
            $model = static::findOne(['alias' => $alias]);

            if (is_null($model)) {
                $model        = new static();
                $model->alias = $alias;
                $model->name  = $alias;
                if ($model->save()) {
                    \Yii::info(sprintf('Создана отсутствующий альбом "%s".', $alias));
                } else {
                    foreach ($model->firstErrors as $firstError) {
                        \Yii::warning(sprintf('Произошла ошибка при попытке создания альбома "%s": %s.', $alias, $firstError));
                    }
                }
            }

            if (!is_null($model)) {
                return Image::find()->from(['{{IMAGE}}' => Image::tableName()])
                            ->innerJoin(['{{ALBUM}}' => Album::tableName()], '{{IMAGE}}.[[album_id]] = {{ALBUM}}.[[id]]')
                            ->where(['{{ALBUM}}.[[alias]]' => $model->alias, '{{ALBUM}}.[[is_active]]' => true])
                            ->andWhere(['{{IMAGE}}.[[is_active]]' => true])
                            ->orderBy(['{{IMAGE}}.[[order]]' => SORT_ASC])
                            ->with('album')->all();
            }

            return [];
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getImages()
        {
            return $this->hasMany(Image::className(), ['album_id' => 'id'])->inverseOf('album');
        }
    }
