<?php

    namespace papalapa\yiistart\modules\images\migrations;

    use yii\db\Migration;

    /**
     * Class m170420_183208_create_images_table
     * @package papalapa\yiistart\modules\images\migrations
     */
    class m170420_183208_create_images_table extends Migration
    {
        /**
         * @inheritdoc
         */
        public function up()
        {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';

            $this->createTable('{{image_category}}', [
                'id'         => $this->primaryKey(),
                'alias'      => $this->string(64)->notNull()->unique(),
                'name'       => $this->string(128)->notNull(),
                'is_visible' => $this->boolean()->unsigned()->notNull()->defaultValue(false),
                'is_active'  => $this->boolean()->unsigned()->notNull()->defaultValue(false),
                'created_by' => $this->integer()->unsigned()->notNull(),
                'updated_by' => $this->integer()->unsigned()->notNull(),
                'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
                'updated_at' => $this->timestamp()->defaultValue(null),
            ], $tableOptions);

            $this->alterColumn('{{image_category}}', '[[id]]', 'INT UNSIGNED NOT NULL AUTO_INCREMENT');

            $this->createTable('{{images}}', [
                'id'          => $this->primaryKey(),
                'category_id' => $this->integer()->unsigned()->notNull(),
                'title'       => $this->string(128)->defaultValue(null),
                'text'        => $this->text()->defaultValue(null),
                'image'       => $this->string(128)->defaultValue(null),
                'size'        => $this->integer()->unsigned()->defaultValue(null),
                'width'       => $this->integer()->unsigned()->defaultValue(null),
                'height'      => $this->integer()->unsigned()->defaultValue(null),
                'order'       => $this->smallInteger()->unsigned()->notNull()->defaultValue(0),
                'is_active'   => $this->boolean()->unsigned()->notNull()->defaultValue(false),
                'created_by'  => $this->integer()->unsigned()->notNull(),
                'updated_by'  => $this->integer()->unsigned()->notNull(),
                'created_at'  => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
                'updated_at'  => $this->timestamp()->defaultValue(null),
            ], $tableOptions);

            $this->alterColumn('{{images}}', '[[id]]', 'INT UNSIGNED NOT NULL AUTO_INCREMENT');
            $this->addForeignKey('fk_images__image_category__id', '{{images}}', ['[[category_id]]'],
                '{{image_category}}', ['[[id]]'],
                'RESTRICT', 'CASCADE');
        }

        /**
         * @inheritdoc
         */
        public function down()
        {
            $this->dropForeignKey('fk_images__image_category__id', '{{images}}');
            $this->dropTable('{{images}}');
            $this->dropTable('{{image_category}}');
        }
    }
