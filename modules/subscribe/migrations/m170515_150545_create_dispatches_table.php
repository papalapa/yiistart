<?php

    use yii\db\Migration;

    /**
     * Handles the creation of table `dispatches`.
     */
    class m170515_150545_create_dispatches_table extends Migration
    {
        /**
         * @inheritdoc
         */
        public function up()
        {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
            $this->createTable('{{dispatches}}', [
                'id'         => $this->primaryKey(),
                'subject'    => $this->string(128)->notNull(),
                'html'       => $this->text()->notNull(),
                'text'       => $this->text()->notNull(),
                'start_at'   => $this->date()->notNull(),
                'status'     => $this->boolean()->notNull()->defaultValue(0),
                'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            ], $tableOptions);

            $this->alterColumn('{{dispatches}}', '[[id]]', 'INT UNSIGNED NOT NULL AUTO_INCREMENT');
        }

        /**
         * @inheritdoc
         */
        public function down()
        {
            $this->dropTable('{{dispatches}}');
        }
    }
