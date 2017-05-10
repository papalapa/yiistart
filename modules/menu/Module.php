<?php

    namespace papalapa\yiistart\modules\menu;

    use papalapa\yiistart\modules\menu\models\Menu;

    /**
     * Class Module
     * @package papalapa\yiistart\modules\menu
     */
    class Module extends \papalapa\yiistart\modules\Module
    {
        /**
         * @var string
         */
        public $controllerNamespace = 'papalapa\yiistart\modules\menu\controllers';
        /**
         * Available menu positions
         * @var array
         */
        public $availablePositions = [Menu::POSITION_TOP, Menu::POSITION_MAIN, Menu::POSITION_BOTTOM];
    }
