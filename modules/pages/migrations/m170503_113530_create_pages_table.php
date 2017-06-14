<?php

    namespace papalapa\yiistart\modules\pages\migrations;

    use yii\db\Migration;

    /**
     * Class m170503_113530_create_pages_table
     * @package papalapa\yiistart\modules\pages\migrations
     */
    class m170503_113530_create_pages_table extends Migration
    {
        /**
         * @inheritdoc
         */
        public function up()
        {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
            $this->createTable('{{pages}}', [
                'id'          => $this->primaryKey(),
                'url'         => $this->string(64)->defaultValue(null),
                'title'       => $this->string(256)->defaultValue(null),
                'description' => $this->string(1024)->defaultValue(null),
                'keywords'    => $this->string(1024)->defaultValue(null),
                'header'      => $this->string(256)->defaultValue(null),
                'text'        => $this->text()->defaultValue(null),
                'has_text'    => $this->boolean()->unsigned()->defaultValue(true),
                'context'     => $this->string(1024)->defaultValue(null),
                'has_context' => $this->boolean()->unsigned()->defaultValue(true),
                'image'       => $this->string(128)->defaultValue(null),
                'has_image'   => $this->boolean()->unsigned()->defaultValue(true),
                'is_active'   => $this->boolean()->unsigned()->notNull()->defaultValue(false),
                'created_by'  => $this->integer()->unsigned()->notNull(),
                'updated_by'  => $this->integer()->unsigned()->notNull(),
                'created_at'  => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
                'updated_at'  => $this->timestamp()->defaultValue(null),
            ], $tableOptions);

            $this->alterColumn('{{pages}}', '[[id]]', 'INT UNSIGNED NOT NULL AUTO_INCREMENT');
        }

        /**
         * @inheritdoc
         */
        public function down()
        {
            $this->dropTable('{{pages}}');
        }
    }
