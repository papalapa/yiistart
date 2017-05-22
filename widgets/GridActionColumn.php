<?php

    namespace papalapa\yiistart\widgets;

    use yii\grid\ActionColumn;

    /**
     * Class GridActionColumn
     * @package papalapa\yiistart\widgets
     */
    class GridActionColumn extends ActionColumn
    {
        /**
         * @var array
         */
        public $permissions = [];

        /**
         * @inheritdoc
         */
        public function init()
        {
            $this->initDefaultButton('view', 'eye-open');
            $this->initDefaultButton('update', 'pencil');
            $this->initDefaultButton('delete', 'trash', [
                'data-confirm' => \Yii::t('yii', 'Are you sure you want to delete this item?'),
                'data-method'  => 'post',
            ]);

            $this->visibleButtons = [
                'view'   => array_key_exists('view', $this->permissions) ? \Yii::$app->user->can($this->permissions['view']) : false,
                'update' => array_key_exists('update', $this->permissions) ? \Yii::$app->user->can($this->permissions['update']) : false,
                'delete' => array_key_exists('delete', $this->permissions) ? \Yii::$app->user->can($this->permissions['delete']) : false,
            ];

            parent::init();
        }
    }
