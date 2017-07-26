<?php

    namespace papalapa\yiistart\modules\i18n\migrations;

    /**
     * Class m170726_105542_create_source_message_categories
     * @package papalapa\yiistart\modules\i18n\migrations
     */
    class m170726_105542_create_source_message_categories extends \m150207_210500_i18n_init
    {
        public function up()
        {
            parent::up();

            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

            $this->createTable('{{%source_message_categories}}', [
                'category'  => $this->string(),
                'translate' => $this->string(),
            ], $tableOptions);

            $this->addPrimaryKey('pk_source_message_categories_category', '{{%source_message_categories}}', ['category']);
        }

        public function down()
        {
            parent::down();

            $this->dropTable('{{%source_message_categories}}');
        }
    }
