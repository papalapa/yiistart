<?php

    namespace papalapa\yiistart\widgets;

    use mihaildev\elfinder\ElFinder;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Inflector;

    /**
     * Class CKEditor
     * To configure toolbars, go to the link:
     * @link    http://ckeditor.com/latest/samples/toolbarconfigurator/index.html#basic
     * @package papalapa\yiistart
     */
    class CKEditor extends \mihaildev\ckeditor\CKEditor
    {
        /**
         * Upload image controller
         * @var string
         */
        public $uploadController = 'upload';
        /**
         * Upload image to subdirectory
         * @var
         */
        public $uploadPath;
        /**
         * @var
         */
        public $fileFilter = ['application/pdf'];
        /**
         * @var array
         */
        public $clientOptions = [
            'inline'        => false, // inline editor or boxed
            'upload'        => true, // allow upload images dialog
            'uploadOptions' => [],
            'height'        => 250, // editor height
            'visually'      => false, // show visual blocks or hide
        ];

        /**
         * @inheritdoc
         */
        public function init()
        {
            if (ArrayHelper::remove($this->clientOptions, 'upload', true)) {
                if (!$this->uploadPath) {
                    $this->uploadPath = Inflector::camel2id((new \ReflectionClass($this->model))->getShortName(), '_');
                }
                $options = ElFinder::ckeditorOptions([
                    $this->uploadController,
                    'path'   => $this->uploadPath,
                    'filter' => $this->fileFilter,
                ], [/* Some CKEditor options */]);
            }

            $options['toolbarGroups'] = [
                ['name' => 'code', 'groups' => ['mode', 'tools']],
                ['name' => 'document', 'groups' => ['document', 'doctools']],
                ['name' => 'clipboard', 'groups' => ['clipboard', 'undo']],
                ['name' => 'editing', 'groups' => ['selection', 'find', 'spellchecker', 'editing']],
                ['name' => 'forms', 'groups' => ['forms']],
                '/',
                ['name' => 'styles', 'groups' => ['styles']],
                ['name' => 'links', 'groups' => ['links']],
                ['name' => 'insert', 'groups' => ['insert']],
                ['name' => 'others', 'groups' => ['others']],
                '/',
                ['name' => 'basicstyles', 'groups' => ['basicstyles', 'cleanup']],
                ['name' => 'colors', 'groups' => ['colors']],
                ['name' => 'paragraph', 'groups' => ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph']],
                ['name' => 'about', 'groups' => ['about']],
            ];

            $options['removeButtons'] = 'Flash,PageBreak,BidiLtr,BidiRtl,Language,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Scayt';

            $this->editorOptions = ArrayHelper::merge($this->clientOptions, $options);

            /**
             * Set visual blocks is visible when start
             */
            if (ArrayHelper::remove($this->clientOptions, 'visually', false)) {
                $this->view->registerJs('CKEDITOR.config.startupOutlineBlocks = true;');
            }

            parent::init();
        }
    }
