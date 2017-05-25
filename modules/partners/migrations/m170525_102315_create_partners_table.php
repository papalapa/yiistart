<?php

    use yii\db\Migration;

    /**
     * Handles the creation of table `partners`.
     */
    class m170525_102315_create_partners_table extends Migration
    {
        /**
         * @inheritdoc
         */
        public function up()
        {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';

            $this->createTable('{{partners}}', [
                'id'         => $this->primaryKey(),
                'url'        => $this->string(256)->defaultValue(null),
                'image'      => $this->string(128)->defaultValue(null),
                'alt'        => $this->string(128)->defaultValue(null),
                'title'      => $this->string(128)->defaultValue(null),
                'order'      => $this->smallInteger()->unsigned()->notNull()->defaultValue(0),
                'is_active'  => $this->boolean()->unsigned()->notNull()->defaultValue(false),
                'created_by' => $this->integer()->unsigned()->notNull(),
                'updated_by' => $this->integer()->unsigned()->notNull(),
                'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
                'updated_at' => $this->timestamp()->defaultValue(null),
            ], $tableOptions);

            $this->alterColumn('{{partners}}', '[[id]]', 'INT UNSIGNED NOT NULL AUTO_INCREMENT');
        }

        /**
         * @inheritdoc
         */
        public function down()
        {
            $this->dropTable('{{partners}}');
        }
    }
