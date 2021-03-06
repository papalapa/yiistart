<?php

    namespace papalapa\yiistart\modules\elements\migrations;

    use papalapa\yiistart\modules\elements\models\Elements;
    use yii\db\Migration;

    /**
     * Class m170419_064317_create_table_elements
     * @package papalapa\yiistart\modules\elements\migrations
     */
    class m170419_064317_create_table_elements extends Migration
    {
        public function up()
        {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';

            $this->createTable('{{element_category}}', [
                'id'         => $this->primaryKey(),
                'name'       => $this->string(64)->notNull(),
                'created_by' => $this->integer()->unsigned()->notNull(),
                'updated_by' => $this->integer()->unsigned()->notNull(),
                'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
                'updated_at' => $this->timestamp()->defaultValue(null),
            ], $tableOptions);

            $this->alterColumn('{{element_category}}', '[[id]]', 'INT UNSIGNED NOT NULL AUTO_INCREMENT');

            $this->createTable('{{elements}}', [
                'id'          => $this->primaryKey(),
                'category_id' => $this->integer()->unsigned()->defaultValue(null),
                'alias'       => $this->string(64)->defaultValue(null)->unique(),
                'name'        => $this->string(128)->defaultValue(null),
                'text'        => $this->text()->defaultValue(null),
                'format'      => $this->string(16)->notNull()->defaultValue(Elements::FORMAT_RAW),
                'pattern'     => $this->string(128)->defaultValue(null),
                'description' => $this->text()->defaultValue(null),
                'is_active'   => $this->boolean()->unsigned()->notNull()->defaultValue(0),
                'created_by'  => $this->integer()->unsigned()->notNull(),
                'updated_by'  => $this->integer()->unsigned()->notNull(),
                'created_at'  => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
                'updated_at'  => $this->timestamp()->defaultValue(null),
            ], $tableOptions);

            $this->alterColumn('{{elements}}', '[[id]]', 'INT UNSIGNED NOT NULL AUTO_INCREMENT');
            $this->addForeignKey('fk_elements__elements_category__id', '{{elements}}', ['[[category_id]]'],
                '{{element_category}}', ['[[id]]'],
                'RESTRICT', 'CASCADE');
        }

        public function down()
        {
            $this->dropForeignKey('fk_elements__elements_category__id', '{{elements}}');
            $this->dropTable('elements');
            $this->dropTable('element_category');
        }
    }
