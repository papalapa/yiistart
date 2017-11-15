<?php

    namespace papalapa\yiistart\modules\image\migrations;

    use yii\db\Migration;

    /**
     * Class m171115_045352_create_image_table
     * Handles the creation of table `image`.
     * @package papalapa\yiistart\modules\image\migrations
     */
    class m171115_045352_create_image_table extends Migration
    {
        /**
         * @inheritdoc
         */
        public function up()
        {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
            $this->createTable('{{app_image}}', [
                'id'            => $this->primaryKey(),
                'album_id'      => $this->integer()->unsigned()->notNull(),
                'name'          => $this->string(512),
                'alt'           => $this->string(256),
                'title'         => $this->string(256),
                'text'          => $this->text(),
                'caption'       => $this->text(),
                'src'           => $this->string(128),
                'cssclass'      => $this->string(128),
                'twin'          => $this->string(128),
                'twin_cssclass' => $this->string(128),
                'link'          => $this->string(256),
                'link_cssclass' => $this->string(128),
                'size'          => $this->integer()->unsigned()->defaultValue(null),
                'width'         => $this->integer()->unsigned()->defaultValue(null),
                'height'        => $this->integer()->unsigned()->defaultValue(null),
                'order'         => $this->smallInteger()->unsigned()->notNull()->defaultValue(0),
                'is_active'     => $this->boolean()->unsigned()->notNull()->defaultValue(false),
                'created_by'    => $this->integer()->unsigned()->notNull(),
                'updated_by'    => $this->integer()->unsigned()->notNull(),
                'created_at'    => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
                'updated_at'    => $this->timestamp()->defaultValue(null),
            ], $tableOptions);

            $this->alterColumn('{{app_image}}', '[[id]]', 'INT UNSIGNED NOT NULL AUTO_INCREMENT');

            $this->addForeignKey('fk_image__album__id', '{{app_image}}', ['[[album_id]]'],
                '{{app_album}}', ['[[id]]'], 'RESTRICT', 'CASCADE');
        }

        /**
         * @inheritdoc
         */
        public function down()
        {
            $this->dropForeignKey('fk_image__album__id', '{{app_image}}');
            $this->dropTable('{{app_image}}');
        }
    }
