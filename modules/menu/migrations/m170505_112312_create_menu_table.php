<?php

    namespace papalapa\yiistart\modules\menu\migrations;

    use papalapa\yiistart\modules\menu\models\Menu;
    use yii\db\Migration;

    /**
     * Class m170505_112312_create_menu_table
     * @package papalapa\yiistart\modules\menu\migrations
     */
    class m170505_112312_create_menu_table extends Migration
    {
        /**
         * @inheritdoc
         */
        public function up()
        {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
            $this->createTable('{{menu}}', [
                'id'         => $this->primaryKey(),
                'parent_id'  => $this->integer()->unsigned(),
                'position'   => $this->string(32)->defaultValue(Menu::POSITION_MAIN),
                'url'        => $this->string(1024)->notNull(),
                'name'       => $this->string(512)->notNull(),
                'image'      => $this->string(128),
                'css_class'  => $this->string(128),
                'template'   => $this->string(128),
                'order'      => $this->smallInteger()->unsigned()->notNull()->defaultValue(0),
                'level'      => $this->smallInteger()->unsigned()->notNull()->defaultValue(0),
                'is_static'  => $this->boolean()->unsigned()->notNull()->defaultValue(0),
                'is_active'  => $this->boolean()->unsigned()->notNull()->defaultValue(0),
                'created_by' => $this->integer()->unsigned()->notNull(),
                'updated_by' => $this->integer()->unsigned()->notNull(),
                'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
                'updated_at' => $this->timestamp()->defaultValue(null),
            ], $tableOptions);

            $this->alterColumn('{{menu}}', '[[id]]', 'INT UNSIGNED NOT NULL AUTO_INCREMENT');
        }

        /**
         * @inheritdoc
         */
        public function down()
        {
            $this->dropTable('{{menu}}');
        }
    }
