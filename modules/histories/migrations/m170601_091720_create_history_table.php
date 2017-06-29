<?php

    namespace papalapa\yiistart\modules\histories\migrations;

    use yii\db\Migration;

    /**
     * Class m170601_091720_create_history_table
     * @package papalapa\yiistart\modules\history\migrations
     */
    class m170601_091720_create_history_table extends Migration
    {
        /**
         * @inheritdoc
         */
        public function up()
        {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
            $this->createTable('{{histories}}', [
                'id'         => $this->primaryKey(),
                'date'       => $this->date()->notNull(),
                'title'      => $this->string(256)->defaultValue(null),
                'text'       => $this->text()->defaultValue(null),
                'image'      => $this->string(128)->defaultValue(null),
                'is_active'  => $this->boolean()->unsigned()->notNull()->defaultValue(0),
                'created_by' => $this->integer()->unsigned()->notNull(),
                'updated_by' => $this->integer()->unsigned()->notNull(),
                'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
                'updated_at' => $this->timestamp()->defaultValue(null),
            ], $tableOptions);

            $this->alterColumn('{{histories}}', '[[id]]', 'INT UNSIGNED NOT NULL AUTO_INCREMENT');
        }

        /**
         * @inheritdoc
         */
        public function down()
        {
            $this->dropTable('{{histories}}');
        }
    }
