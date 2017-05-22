<?php

    namespace papalapa\yiistart\modules\pages\migrations;

    use yii\db\Migration;

    /**
     * Class m170504_043149_create_table_pages_translation
     * @package papalapa\yiistart\modules\pages\migrations
     */
    class m170504_043149_create_table_pages_translation extends Migration
    {
        /**
         * @inheritdoc
         */
        public function up()
        {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
            $this->createTable('{{pages_translation}}', [
                'id'          => $this->primaryKey(),
                'language'    => $this->string(16)->notNull(),
                'content_id'  => $this->integer()->unsigned()->notNull(),
                'title'       => $this->string(256)->defaultValue(null),
                'description' => $this->string(1024)->defaultValue(null),
                'keywords'    => $this->string(1024)->defaultValue(null),
                'header'      => $this->string(256)->defaultValue(null),
                'text'        => $this->text()->defaultValue(null),
                'context'     => $this->string(1024)->defaultValue(null),
            ], $tableOptions);

            $this->alterColumn('{{pages_translation}}', '[[id]]', 'INT UNSIGNED NOT NULL AUTO_INCREMENT');
            $this->createIndex('idx_pages_translation__content_id', '{{pages_translation}}', ['[[content_id]]']);
            $this->createIndex('idx_pages_translation__language', '{{pages_translation}}', ['[[language]]']);
            $this->createIndex('idx_pages_translation__language_content_id', '{{pages_translation}}', ['[[language]]', '[[content_id]]'], true);
            $this->addForeignKey('fk_pages_translation__pages__content_id__pages_id', '{{pages_translation}}', ['[[content_id]]'],
                '{{pages}}', ['[[id]]'],
                'CASCADE', 'CASCADE');
        }

        /**
         * @inheritdoc
         */
        public function down()
        {
            $this->dropForeignKey('fk_pages_translation__pages__content_id__pages_id', '{{pages_translation}}');
            $this->dropTable('{{pages_translation}}');
        }
    }
