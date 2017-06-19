<?php

    namespace papalapa\yiistart\controllers;

    use papalapa\yiistart\helpers\FileHelper;
    use papalapa\yiistart\models\BelongingImages;
    use papalapa\yiistart\models\ImageUploadForm;
    use papalapa\yiistart\modules\settings\models\Settings;
    use yii\bootstrap\Html;
    use yii\filters\AccessControl;
    use yii\filters\VerbFilter;
    use yii\helpers\Json;
    use yii\helpers\Url;
    use yii\web\Controller;
    use yii\web\UploadedFile;

    /**
     * Class ImageUploadController
     * @package papalapa\yiistart\controllers
     */
    class ImageUploadController extends Controller
    {
        /**
         * Directory on frontend to upload images
         * @var string
         */
        public $storage = 'files';

        /**
         * @inheritdoc
         */
        public function behaviors()
        {
            return [
                'verbs'  => [
                    'class'   => VerbFilter::className(),
                    'actions' => [
                        'upload' => ['post'],
                        'delete' => ['post'],
                    ],
                ],
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['manager', 'admin', 'developer'],
                        ],
                    ],
                ],
            ];
        }

        /**
         * @param $path
         * @param $token
         * @return string
         */
        public function actionDelete($path, $token)
        {
            if (strcmp($path, \Yii::$app->security->decryptByKey($token, Settings::valueOf('security.hash.token'))) !== 0) {
                return Json::encode(['error' => 'Ссылка некорректна']);
            }

            BelongingImages::deleteAll(['path' => $path]);
            FileHelper::delete(\Yii::getAlias('@frontend/web'.$path));

            return Json::encode([]);
        }

        /**
         * @param $formName
         * @return string
         */
        public function actionUpload($formName)
        {
            $data = [];

            if (\Yii::$app->request->isPost) {
                $uploadForm        = new ImageUploadForm();
                $uploadForm->image = UploadedFile::getInstanceByName('image');
                if ($uploadForm->upload($this->storage)) {
                    $data = [
                        'initialPreview'       => [
                            Html::img($uploadForm->path, ['class' => 'file-preview-image', 'style' => 'max-height:100%;max-width:100%;'])
                            .Html::hiddenInput(sprintf('%s[_images][]', $formName), $uploadForm->path),
                        ],
                        'initialPreviewConfig' => [
                            [
                                'caption'   => basename($uploadForm->path),
                                'frameAttr' => [
                                    'style' => 'height:160px',
                                ],
                                'url'       => Url::to([
                                    '/image-upload/delete',
                                    'path'  => $uploadForm->path,
                                    'token' => \Yii::$app->security->encryptByKey($uploadForm->path,
                                        Settings::valueOf('security.hash.token')),
                                ]),
                            ],
                        ],
                        'initialCaption'       => basename($uploadForm->path),
                    ];
                } else {
                    $data['error'] = reset($uploadForm->firstErrors);
                }
            }

            return Json::encode($data);
        }
    }
