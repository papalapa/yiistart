<?php

    use yii\db\Migration;

    /**
     * Class m151120_044328_create_user_table
     */
    class m151120_044328_create_user_table extends Migration
    {
        /**
         * @inheritdoc
         */
        public function up()
        {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
            $this->createTable('{{user}}', [
                'id'            => $this->primaryKey(),
                'email'         => $this->string(128)->unique()->notNull(),
                'auth_key'      => $this->string(32)->unique(),
                'password_hash' => $this->string(128)->notNull(),
                'token'         => $this->string(128)->unique()->defaultValue(null),
                'status'        => $this->smallInteger()->notNull()->defaultValue(0),
                'role'          => $this->boolean()->notNull()->defaultValue(0),
                'created_at'    => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
                'updated_at'    => $this->timestamp()->defaultValue(null),
            ], $tableOptions);
            $this->alterColumn('{{user}}', '[[id]]', 'INT UNSIGNED NOT NULL AUTO_INCREMENT');
        }

        /**
         * @inheritdoc
         */
        public function down()
        {
            $this->dropTable('{{user}}');
        }
    }
