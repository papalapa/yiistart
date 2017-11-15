<?php

    namespace papalapa\yiistart\modules\image\migrations;

    use yii\db\Migration;

    /**
     * Class m171115_035551_create_album_table
     * Handles the creation of table `album`.
     * @package papalapa\yiistart\modules\image\migrations
     */
    class m171115_035551_create_album_table extends Migration
    {
        /**
         * @inheritdoc
         */
        public function up()
        {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
            $this->createTable('{{app_album}}', [
                'id'                     => $this->primaryKey(),
                'alias'                  => $this->string(128)->notNull()->unique(),
                'name'                   => $this->string(256)->notNull(),
                'scale'                  => $this->integer()->unsigned(),
                'template'               => $this->string(1024),
                'has_name'               => $this->boolean()->unsigned()->notNull()->defaultValue(false),
                'has_alt'                => $this->boolean()->unsigned()->notNull()->defaultValue(false),
                'has_title'              => $this->boolean()->unsigned()->notNull()->defaultValue(false),
                'has_text'               => $this->boolean()->unsigned()->notNull()->defaultValue(false),
                'has_caption'            => $this->boolean()->unsigned()->notNull()->defaultValue(false),
                'has_src'                => $this->boolean()->unsigned()->notNull()->defaultValue(false),
                'has_cssclass'           => $this->boolean()->unsigned()->notNull()->defaultValue(false),
                'has_twin'               => $this->boolean()->unsigned()->notNull()->defaultValue(false),
                'has_twin_cssclass'      => $this->boolean()->unsigned()->notNull()->defaultValue(false),
                'has_link'               => $this->boolean()->unsigned()->notNull()->defaultValue(false),
                'has_link_cssclass'      => $this->boolean()->unsigned()->notNull()->defaultValue(false),
                'validator_controller'   => $this->string(128),
                'validator_extensions'   => $this->string(128),
                'validator_min_size'     => $this->integer()->unsigned(),
                'validator_max_size'     => $this->integer()->unsigned(),
                'description'            => $this->text(),
                'is_multilingual_images' => $this->boolean()->unsigned()->notNull()->defaultValue(false),
                'is_visible'             => $this->boolean()->unsigned()->notNull()->defaultValue(false),
                'is_active'              => $this->boolean()->unsigned()->notNull()->defaultValue(false),
                'created_by'             => $this->integer()->unsigned()->notNull(),
                'updated_by'             => $this->integer()->unsigned()->notNull(),
                'created_at'             => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
                'updated_at'             => $this->timestamp()->defaultValue(null),
            ], $tableOptions);

            $this->alterColumn('{{app_album}}', '[[id]]', 'INT UNSIGNED NOT NULL AUTO_INCREMENT');
        }

        /**
         * @inheritdoc
         */
        public function down()
        {
            $this->dropTable('{{app_album}}');
        }
    }
