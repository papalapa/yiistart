<?php

    namespace papalapa\yiistart\modules\images\migrations;

    use yii\db\Migration;

    /**
     * Class m170420_184016_create_images_translation_table
     * @package papalapa\yiistart\modules\images\migrations
     */
    class m170420_184016_create_images_translation_table extends Migration
    {
        /**
         * @inheritdoc
         */
        public function up()
        {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
            $this->createTable('{{images_translation}}', [
                'id'         => $this->primaryKey(),
                'language'   => $this->string(16)->notNull(),
                'content_id' => $this->integer()->unsigned()->notNull(),
                'title'      => $this->string(128)->defaultValue(null),
                'text'       => $this->text()->defaultValue(null),
            ], $tableOptions);

            $this->alterColumn('{{images_translation}}', '[[id]]', 'INT UNSIGNED NOT NULL AUTO_INCREMENT');
            $this->createIndex('idx_images_translation_content_id', '{{images_translation}}', ['[[content_id]]']);
            $this->createIndex('idx_images_translation_language', '{{images_translation}}', ['[[language]]']);
            $this->createIndex('idx_images_translation_language_content_id', '{{images_translation}}', ['[[language]]', '[[content_id]]'], true);
            $this->addForeignKey('fk_images_translation_content_id__images_id', '{{images_translation}}', ['[[content_id]]'],
                '{{images}}', ['[[id]]'], 'CASCADE', 'CASCADE');
        }

        /**
         * @inheritdoc
         */
        public function down()
        {
            $this->dropForeignKey('fk_images_translation_content_id__images_id', '{{images_translation}}');
            $this->dropTable('{{images_translation}}');
        }
    }
