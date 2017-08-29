<?php

    namespace papalapa\yiistart\modules\social\migrations;

    use yii\db\Migration;

    /**
     * Class m170829_054229_create_social_table
     * @package papalapa\yiistart\modules\social\migrations
     */
    class m170829_054229_create_social_table extends Migration
    {
        /**
         * @inheritdoc
         */
        public function up()
        {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';

            $this->createTable('{{social}}', [
                'id'         => $this->primaryKey(),
                'name'       => $this->string(128),
                'position'   => $this->string(128),
                'url'        => $this->string(128),
                'image'      => $this->string(128),
                'title'      => $this->string(128),
                'alt'        => $this->string(128),
                'order'      => $this->smallInteger()->unsigned()->notNull()->defaultValue(0),
                'is_active'  => $this->boolean()->unsigned()->notNull()->defaultValue(false),
                'created_by' => $this->integer()->unsigned()->notNull(),
                'updated_by' => $this->integer()->unsigned()->notNull(),
                'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
                'updated_at' => $this->timestamp()->defaultValue(null),
            ], $tableOptions);

            $this->alterColumn('{{social}}', '[[id]]', 'INT UNSIGNED NOT NULL AUTO_INCREMENT');
        }

        /**
         * @inheritdoc
         */
        public function down()
        {
            $this->dropTable('social');
        }
    }
