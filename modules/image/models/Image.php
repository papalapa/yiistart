<?php

    namespace papalapa\yiistart\modules\image\models;

    use papalapa\yiistart\helpers\FileHelper;
    use papalapa\yiistart\models\MultilingualActiveRecord;
    use papalapa\yiistart\validators\FilePathValidator;
    use papalapa\yiistart\validators\ReorderValidator;
    use yii\behaviors\BlameableBehavior;
    use yii\behaviors\TimestampBehavior;
    use yii\db\ActiveQuery;
    use yii\helpers\ArrayHelper;

    /**
     * This is the model class for table "app_image".
     *
     * @property integer            $id
     * @property integer            $album_id
     * @property string             $name
     * @property string             $alt
     * @property string             $title
     * @property string             $text
     * @property string             $caption
     * @property string             $src
     * @property string             $cssclass
     * @property string             $twin
     * @property string             $twin_cssclass
     * @property string             $link
     * @property string             $link_cssclass
     * @property integer            $size
     * @property integer            $width
     * @property integer            $height
     * @property integer            $order
     * @property integer            $is_active
     * @property integer            $created_by
     * @property integer            $updated_by
     * @property string             $created_at
     * @property string             $updated_at
     *
     * @property Album              $album
     * @property ImageTranslation[] $translations
     */
    class Image extends MultilingualActiveRecord
    {
        const SCENARIO_DEVELOPER = 'developer';

        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'app_image';
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return $this->localizedAttributes([
                'id'            => 'ID',
                'album_id'      => 'Альбом',
                'name'          => 'Название',
                'alt'           => 'Атрибут IMG:Alt',
                'title'         => 'Атрибут IMG:Title',
                'text'          => 'Текст',
                'caption'       => 'Описание',
                'src'           => 'Изображение',
                'cssclass'      => 'CSS класс изображения',
                'twin'          => 'Изображение-связка',
                'twin_cssclass' => 'CSS класс изображения-связки',
                'link'          => 'Ссылка',
                'link_cssclass' => 'CSS класс ссылки',
                'size'          => 'Размер',
                'width'         => 'Ширина',
                'height'        => 'Высота',
                'order'         => 'Порядок',
                'is_active'     => 'Активность',
                'created_by'    => 'Кем создано',
                'updated_by'    => 'Кем изменено',
                'created_at'    => 'Дата создания',
                'updated_at'    => 'Дата изменения',
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
                    'value' => date('Y-m-d H:i:s'),
                ],
                'BlameableBehavior'    => [
                    'class' => BlameableBehavior::className(),
                ],
                'MultilingualBehavior' => [
                    'langClassName' => ImageTranslation::className(),
                    'tableName'     => ImageTranslation::tableName(),
                    'attributes'    => ['name', 'alt', 'title', 'src', 'twin', 'text', 'caption'],
                ],
            ]);
        }

        /**
         * @return array
         */
        public function scenarios()
        {
            return $this->localizedScenarios([
                self::SCENARIO_DEFAULT   => ['album_id', 'text', 'caption', 'name', 'alt', 'title', 'link', 'cssclass', 'twin_cssclass', 'link_cssclass', 'order', 'is_active', 'src', 'twin'],
                self::SCENARIO_DEVELOPER => ['album_id', 'text', 'caption', 'name', 'alt', 'title', 'link', 'cssclass', 'twin_cssclass', 'link_cssclass', 'order', 'is_active', 'src', 'twin'],
            ]);
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return $this->localizedRules([
                [['album_id'], 'required'],
                [['album_id'], 'integer'],
                [
                    ['album_id'], 'exist',
                    'targetClass'     => Album::className(),
                    'targetAttribute' => 'id',
                    'filter'          => function (ActiveQuery $q) {
                        return $q->andFilterWhere(['is_visible' => $this->scenario == self::SCENARIO_DEFAULT ? true : null]);
                    },
                ],

                [['is_active'], 'boolean'],
                [['is_active'], 'default', 'value' => false],

                [['order'], 'integer', 'min' => 0],
                [['order'], ReorderValidator::className(), 'extraFields' => ['album_id']],
                [['order'], 'required'],

                [['text', 'caption'], 'string'],
                [['name'], 'string', 'max' => 128],
                [['alt', 'title'], 'string', 'max' => 64],
                [['link'], 'string', 'max' => 128],
                [['cssclass', 'twin_cssclass', 'link_cssclass'], 'string', 'max' => 128],

                [['src', 'twin'], 'string', 'max' => 128, 'enableClientValidation' => false],
                [['src', 'twin'], FilePathValidator::className()],
            ]);
        }

        /**
         * @return bool
         */
        public function beforeValidate()
        {
            if ($album = Album::findOne($this->album_id)) {
                if (!empty($album->validator_extensions) || !empty($album->validator_min_size) || !empty($album->validator_max_size)) {
                    $extensions = preg_split('/[\,|\;|\s]/', $album->validator_extensions, -1, PREG_SPLIT_NO_EMPTY);
                    $this->validators->append([
                        ['src', 'twin'], 'file',
                        'extensions' => (array) $extensions ? : null,
                        'minSize'    => $album->validator_min_size ? : null,
                        'maxSize'    => $album->validator_max_size ? : null,
                    ]);
                }
            }

            return parent::beforeValidate(); // TODO: Change the autogenerated stub
        }

        /**
         * @param bool $insert
         * @return bool
         */
        public function beforeSave($insert)
        {
            if (parent::beforeSave($insert)) {

                $path = \Yii::getAlias("@frontend/web{$this->src}");
                if (FileHelper::is_file($path) && $info = getimagesize($path)) {
                    $this->width  = ArrayHelper::getValue($info, 0);
                    $this->height = ArrayHelper::getValue($info, 1);
                    $this->size   = FileHelper::size($path);
                }

                return true;
            }

            return false;
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getAlbum()
        {
            return $this->hasOne(Album::className(), ['id' => 'album_id'])->inverseOf('images');
        }

        /**
         * @return \yii\db\ActiveQuery
         */
        public function getTranslations()
        {
            return $this->hasMany(ImageTranslation::className(), ['content_id' => 'id']);
        }
    }
