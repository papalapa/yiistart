<?php

    use yii\db\Migration;

    /**
     * Class m170510_044259_create_table_menu_translation
     */
    class m170510_044259_create_table_menu_translation extends Migration
    {
        /**
         * @inheritdoc
         */
        public function up()
        {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
            $this->createTable('{{menu_translation}}', [
                'id'         => $this->primaryKey(),
                'language'   => $this->string(16)->notNull(),
                'content_id' => $this->integer()->unsigned()->notNull(),
                'title'      => $this->string(64)->notNull(),
            ], $tableOptions);

            $this->alterColumn('{{menu_translation}}', '[[id]]', 'INT UNSIGNED NOT NULL AUTO_INCREMENT');
            $this->createIndex('idx_menu_translation__content_id', '{{menu_translation}}', ['[[content_id]]']);
            $this->createIndex('idx_menu_translation__language', '{{menu_translation}}', ['[[language]]']);
            $this->createIndex('idx_menu_translation__language_content_id', '{{menu_translation}}',
                ['[[language]]', '[[content_id]]'], true);
            $this->addForeignKey('fk_menu_translation__menu__content_id__menu_id', '{{menu_translation}}', ['[[content_id]]'],
                '{{menu}}', ['[[id]]'],
                'CASCADE', 'CASCADE');
        }

        /**
         * @inheritdoc
         */
        public function down()
        {
            $this->dropForeignKey('fk_menu_translation__menu__content_id__menu_id', '{{menu_translation}}');
            $this->dropTable('{{menu_translation}}');
        }
    }
