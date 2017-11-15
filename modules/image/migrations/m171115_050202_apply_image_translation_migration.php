<?php

    namespace papalapa\yiistart\modules\image\migrations;

    use yii\db\Migration;

    /**
     * Class m171115_050202_apply_image_translation_migration
     * @package papalapa\yiistart\modules\image\migrations
     */
    class m171115_050202_apply_image_translation_migration extends Migration
    {
        public $table            = 'app_image';
        public $translationTable = 'app_image_translation';

        public function up()
        {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
            $this->createTable("{{{$this->translationTable}}}", [
                'id'         => $this->primaryKey(),
                'language'   => $this->string(16)->notNull(),
                'content_id' => $this->integer()->unsigned()->notNull(),
                'name'       => $this->string(512),
                'alt'        => $this->string(256),
                'title'      => $this->string(256),
                'src'        => $this->string(128),
                'twin'       => $this->string(128),
                'text'       => $this->text(),
                'caption'    => $this->text(),
            ], $tableOptions);

            $this->alterColumn("{{{$this->translationTable}}}", '[[id]]', 'INT UNSIGNED NOT NULL AUTO_INCREMENT');
            $this->createIndex("idx_{$this->translationTable}_content_id", "{{{$this->translationTable}}}", ['[[content_id]]']);
            $this->createIndex("idx_{$this->translationTable}_language", "{{{$this->translationTable}}}", ['[[language]]']);
            $this->createIndex("idx_{$this->translationTable}_language_content_id", "{{{$this->translationTable}}}", ['[[language]]', '[[content_id]]'], true);
            $this->addForeignKey("fk_{$this->translationTable}__{$this->table}", "{{{$this->translationTable}}}", ['[[content_id]]'],
                "{{{$this->table}}}", ['[[id]]'], 'CASCADE', 'CASCADE');
        }

        public function down()
        {
            $this->dropForeignKey("fk_{$this->translationTable}__{$this->table}", "{{{$this->translationTable}}}");
            $this->dropTable("{{{$this->translationTable}}}");
        }
    }
