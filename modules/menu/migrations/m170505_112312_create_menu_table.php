<?php

    use papalapa\yiistart\modules\menu\Module;
    use yii\db\Migration;

    /**
     * Handles the creation of table `menu`.
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
                'position'   => $this->string(32)->defaultValue(Module::POSITION_MAIN),
                'url'        => $this->string(64)->notNull(),
                'title'      => $this->string(64)->notNull(),
                'sort'       => $this->smallInteger()->unsigned()->notNull(),
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
            $this->dropTable('menu');
        }
    }