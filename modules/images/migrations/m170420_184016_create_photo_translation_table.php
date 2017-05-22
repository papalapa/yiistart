<?php

    use yii\db\Migration;

    /**
     * Handles the creation of table `photo_translation`.
     */
    class m170420_184016_create_photo_translation_table extends Migration
    {
        /**
         * @inheritdoc
         */
        public function up()
        {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
            $this->createTable('{{photo_translation}}', [
                'id'         => $this->primaryKey(),
                'language'   => $this->string(16)->notNull(),
                'content_id' => $this->integer()->unsigned()->notNull(),
                'title'      => $this->string(128)->defaultValue(null),
                'text'       => $this->text()->defaultValue(null),
            ], $tableOptions);

            $this->alterColumn('{{photo_translation}}', '[[id]]', 'INT UNSIGNED NOT NULL AUTO_INCREMENT');
            $this->createIndex('idx_photo_translation_content_id', '{{photo_translation}}', ['[[content_id]]']);
            $this->createIndex('idx_photo_translation_language', '{{photo_translation}}', ['[[language]]']);
            $this->createIndex('idx_photo_translation_language_content_id', '{{photo_translation}}', ['[[language]]', '[[content_id]]'], true);
            $this->addForeignKey('fk_photo_translation_content_id__photo_id', '{{photo_translation}}', ['[[content_id]]'],
                '{{photo}}', ['[[id]]'],
                'CASCADE', 'CASCADE');
        }

        /**
         * @inheritdoc
         */
        public function down()
        {
            $this->dropForeignKey('fk_photo_translation_content_id__photo_id', '{{photo_translation}}');
            $this->dropTable('{{photo_translation}}');
        }
    }
