<?php

    use yii\db\Migration;

    /**
     * Handles the creation of table `pages`.
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
                'title'       => $this->string(256)->notNull(),
                'description' => $this->string(1024)->defaultValue(null),
                'keywords'    => $this->string(1024)->defaultValue(null),
                'header'      => $this->string(256)->defaultValue(null),
                'context'     => $this->string(1024)->defaultValue(null),
                'text'        => $this->text()->notNull(),
                'image'       => $this->string(128)->defaultValue(null),
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
