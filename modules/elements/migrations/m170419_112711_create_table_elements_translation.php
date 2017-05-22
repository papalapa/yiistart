<?php

    namespace papalapa\yiistart\modules\elements\migrations;

    use yii\db\Migration;

    /**
     * Class m170419_112711_create_table_elements_translation
     * @package papalapa\yiistart\modules\elements\migrations
     */
    class m170419_112711_create_table_elements_translation extends Migration
    {
        /**
         * @inheritdoc
         */
        public function up()
        {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
            $this->createTable('{{elements_translation}}', [
                'id'         => $this->primaryKey(),
                'language'   => $this->string(16)->notNull(),
                'content_id' => $this->integer()->unsigned()->notNull(),
                'text'       => $this->text()->notNull(),
            ], $tableOptions);

            $this->alterColumn('{{elements_translation}}', '[[id]]', 'INT UNSIGNED NOT NULL AUTO_INCREMENT');
            $this->createIndex('idx_elements_translation__content_id', '{{elements_translation}}', ['[[content_id]]']);
            $this->createIndex('idx_elements_translation__language', '{{elements_translation}}', ['[[language]]']);
            $this->createIndex('idx_elements_translation__language_content_id', '{{elements_translation}}', ['[[language]]', '[[content_id]]'], true);
            $this->addForeignKey('fk_elements_translation__elements__id', '{{elements_translation}}', ['[[content_id]]'],
                '{{elements}}', ['[[id]]'], 'CASCADE', 'CASCADE');
        }

        /**
         * @inheritdoc
         */
        public function down()
        {
            $this->dropForeignKey('fk_elements_translation__elements__id', '{{elements_translation}}');
            $this->dropTable('{{elements_translation}}');
        }
    }
