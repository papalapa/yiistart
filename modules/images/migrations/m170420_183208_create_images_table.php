<?php

    namespace papalapa\yiistart\modules\images\migrations;

    use yii\db\Migration;

    /**
     * Class m170420_183208_create_images_table
     * Handles the creation of table `images`.
     */
    class m170420_183208_create_images_table extends Migration
    {
        /**
         * @inheritdoc
         */
        public function up()
        {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
            $this->createTable('{{images}}', [
                'id'         => $this->primaryKey(),
                'title'      => $this->string(128)->defaultValue(null),
                'text'       => $this->text()->defaultValue(null),
                'image'      => $this->string(128)->defaultValue(null),
                'size'       => $this->integer()->unsigned()->defaultValue(null),
                'width'      => $this->integer()->unsigned()->defaultValue(null),
                'height'     => $this->integer()->unsigned()->defaultValue(null),
                'order'      => $this->smallInteger()->unsigned()->notNull()->defaultValue(0),
                'is_active'  => $this->boolean()->unsigned()->notNull()->defaultValue(false),
                'created_by' => $this->integer()->unsigned()->notNull(),
                'updated_by' => $this->integer()->unsigned()->notNull(),
                'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
                'updated_at' => $this->timestamp()->defaultValue(null),
            ], $tableOptions);

            $this->alterColumn('{{images}}', '[[id]]', 'INT UNSIGNED NOT NULL AUTO_INCREMENT');
        }

        /**
         * @inheritdoc
         */
        public function down()
        {
            $this->dropTable('{{images}}');
        }
    }
