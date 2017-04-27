<?php

    namespace papalapa\yiistart\assets;

    use yii\web\AssetBundle;

    /**
     * Class CDN_Datatables_Datatables_Asset
     * @link    https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.15/js/jquery.dataTables.min.js
     * @package papalapa\yiistart\assets
     */
    class CDN_Datatables_Datatables_Asset extends AssetBundle
    {
        public $bootstrap = false;
        public $css       = [
            'https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.15/css/jquery.dataTables.min.css',
        ];
        public $js        = [
            'https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.15/js/jquery.dataTables.min.js',
        ];

        public function init()
        {
            if ($this->bootstrap) {
                $this->css = [
                    'https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.15/css/dataTables.bootstrap.min.css',
                ];
                $this->js  = [
                    'https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.15/js/dataTables.bootstrap.min.js',
                ];
            }

            parent::init();
        }
    }
