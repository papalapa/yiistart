<?php

    namespace papalapa\yiistart\modules\settings\migrations;

    use yii\db\Migration;

    /**
     * Class m170426_102549_create_settings_table
     * @package papalapa\yiistart\modules\settings\migrations
     */
    class m170426_102549_create_settings_table extends Migration
    {
        /**
         * @inheritdoc
         */
        public function up()
        {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
            $this->createTable('{{settings}}', [
                'id'         => $this->primaryKey(),
                'title'      => $this->string(64)->defaultValue(null),
                'key'        => $this->string(64)->notNull()->unique(),
                'value'      => $this->text()->defaultValue(null),
                'comment'    => $this->string(1024)->defaultValue(null),
                'is_active'  => $this->boolean()->unsigned()->notNull()->defaultValue(false),
                'created_by' => $this->integer()->unsigned()->notNull(),
                'updated_by' => $this->integer()->unsigned()->notNull(),
                'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
                'updated_at' => $this->timestamp()->defaultValue(null),
            ], $tableOptions);

            $this->alterColumn('{{settings}}', '[[id]]', 'INT UNSIGNED NOT NULL AUTO_INCREMENT');
        }

        /**
         * @inheritdoc
         */
        public function down()
        {
            $this->dropTable('{{settings}}');
        }
    }
