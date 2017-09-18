<?php

    namespace papalapa\yiistart\modules\banners\migrations;

    use yii\db\Migration;

    /**
     * Class m170712_120124_create_banners_table
     * @package papalapa\yiistart\modules\banners\migrations
     */
    class m170712_120124_create_banners_table extends Migration
    {
        /**
         * @inheritdoc
         */
        public function up()
        {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';

            $this->createTable('{{banners_category}}', [
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

            $this->alterColumn('{{banners_category}}', '[[id]]', 'INT UNSIGNED NOT NULL AUTO_INCREMENT');

            $this->createTable('{{banners}}', [
                'id'          => $this->primaryKey(),
                'category_id' => $this->integer()->unsigned()->defaultValue(null),
                'title'       => $this->string(256)->defaultValue(null),
                'text'        => $this->text()->defaultValue(null),
                'link'        => $this->string(1024)->defaultValue(null),
                'image'       => $this->string(128)->notNull(),
                'order'       => $this->smallInteger()->unsigned()->notNull()->defaultValue(0),
                'is_active'   => $this->boolean()->unsigned()->notNull()->defaultValue(false),
                'created_by'  => $this->integer()->unsigned()->notNull(),
                'updated_by'  => $this->integer()->unsigned()->notNull(),
                'created_at'  => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
                'updated_at'  => $this->timestamp()->defaultValue(null),
            ], $tableOptions);

            $this->alterColumn('{{banners}}', '[[id]]', 'INT UNSIGNED NOT NULL AUTO_INCREMENT');

            $this->addForeignKey('fk_banners__banners_category__id', '{{banners}}', ['[[category_id]]'],
                '{{banners_category}}', ['[[id]]'],
                'RESTRICT', 'CASCADE');
        }

        /**
         * @inheritdoc
         */
        public function down()
        {
            $this->dropForeignKey('fk_banners__banners_category__id', '{{banners}}');
            $this->dropTable('{{banners}}');
            $this->dropTable('{{banners_category}}');
        }
    }
