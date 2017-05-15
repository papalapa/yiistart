<?php

    use yii\db\Migration;

    /**
     * Handles the creation of table `subscribers`.
     */
    class m170515_150533_create_subscribers_table extends Migration
    {
        /**
         * @inheritdoc
         */
        public function up()
        {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
            $this->createTable('{{subscribers}}', [
                'id'         => $this->primaryKey(),
                'email'      => $this->string(128)->notNull()->unique(),
                'status'     => $this->boolean()->unsigned()->notNull()->defaultValue(1),
                'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            ], $tableOptions);

            $this->alterColumn('{{subscribers}}', '[[id]]', 'INT UNSIGNED NOT NULL AUTO_INCREMENT');
        }

        /**
         * @inheritdoc
         */
        public function down()
        {
            $this->dropTable('{{subscribers}}');
        }
    }
