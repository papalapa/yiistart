<?php

    use yii\db\Migration;

    /**
     * Handles the creation of table `settings`.
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
                'key'        => $this->string(64)->notNull()->unique(),
                'value'      => $this->text(),
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
