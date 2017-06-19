<?php

    namespace papalapa\yiistart\migrations;

    use yii\db\Migration;

    /**
     * Class m160719_061258_create_table_tags
     * @package papalapa\yiistart\migrations
     */
    class m160719_061258_create_table_tags extends Migration
    {
        public function up()
        {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=MyISAM';
            $this->createTable('{{belonging_tags}}', [
                'id'           => $this->primaryKey(),
                'content_type' => $this->string(32)->defaultValue(null),
                'content_id'   => $this->integer()->unsigned()->defaultValue(null),
                'tag'          => $this->string(128)->notNull(),
                'order'        => $this->smallInteger()->unsigned()->notNull()->defaultValue(0),
                'is_outdated'  => $this->boolean()->unsigned()->notNull()->defaultValue(0),
                'created_by'   => $this->integer()->unsigned()->notNull(),
                'updated_by'   => $this->integer()->unsigned()->notNull(),
                'created_at'   => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
                'updated_at'   => $this->timestamp()->defaultValue(null),
            ], $tableOptions);

            $this->alterColumn('{{belonging_tags}}', '[[id]]', 'INT UNSIGNED NOT NULL AUTO_INCREMENT');
            $this->createIndex('unq_tag_entity', '{{belonging_tags}}', ['[[content_type]]', '[[content_id]]', '[[tag]]'], true);
            $this->createIndex('tag', '{{belonging_tags}}', '[[tag]]');
        }

        public function down()
        {
            $this->dropTable('{{belonging_tags}}');
        }
    }
