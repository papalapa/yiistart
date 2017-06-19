<?php

    namespace papalapa\yiistart\migrations;

    use yii\db\Migration;

    /**
     * Class m160719_061642_create_table_images
     * @package papalapa\yiistart\migrations
     */
    class m160719_061642_create_table_images extends Migration
    {
        public function up()
        {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=MyISAM';
            $this->createTable('{{belonging_images}}', [
                'id'           => $this->primaryKey(),
                'content_type' => $this->string(32)->defaultValue(null),
                'content_id'   => $this->integer()->unsigned()->defaultValue(null),
                'path'         => $this->string(128)->notNull(),
                'size'         => $this->integer()->unsigned()->defaultValue(null),
                'width'        => $this->smallInteger()->unsigned()->defaultValue(null),
                'height'       => $this->smallInteger()->unsigned()->defaultValue(null),
                'order'        => $this->smallInteger()->unsigned()->notNull()->defaultValue(0),
                'is_outdated'     => $this->boolean()->unsigned()->defaultValue(0),
                'created_by'   => $this->integer()->unsigned()->notNull(),
                'updated_by'   => $this->integer()->unsigned()->notNull(),
                'created_at'   => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
                'updated_at'   => $this->timestamp()->defaultValue(null),
            ], $tableOptions);

            $this->alterColumn('{{belonging_images}}', '[[id]]', 'INT UNSIGNED NOT NULL AUTO_INCREMENT');
            $this->createIndex('unq_image_entity', '{{belonging_images}}', ['[[content_type]]', '[[content_id]]', '[[path]]'], true);
        }

        public function down()
        {
            $this->dropTable('{{belonging_images}}');
        }
    }
