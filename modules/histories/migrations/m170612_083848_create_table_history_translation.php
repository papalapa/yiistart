<?php

    namespace papalapa\yiistart\modules\histories\migrations;

    use yii\db\Migration;

    /**
     * Class m170612_083848_create_table_history_translation
     * @package papalapa\yiistart\modules\histories\migrations
     */
    class m170612_083848_create_table_history_translation extends Migration
    {
        public $table            = 'histories';
        public $translationTable = 'histories_translation';

        public function up()
        {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
            $this->createTable("{{{$this->translationTable}}}", [
                'id'         => $this->primaryKey(),
                'language'   => $this->string(16)->notNull(),
                'content_id' => $this->integer()->unsigned()->notNull(),
                'title'      => $this->string(256)->defaultValue(null),
                'text'       => $this->text()->defaultValue(null),
            ], $tableOptions);

            $this->alterColumn("{{{$this->translationTable}}}", '[[id]]', 'INT UNSIGNED NOT NULL AUTO_INCREMENT');
            $this->createIndex("idx_{$this->translationTable}_content_id", "{{{$this->translationTable}}}", ['[[content_id]]']);
            $this->createIndex("idx_{$this->translationTable}_language", "{{{$this->translationTable}}}", ['[[language]]']);
            $this->createIndex("idx_{$this->translationTable}_language_content_id", "{{{$this->translationTable}}}",
                ['[[language]]', '[[content_id]]'], true);
            $this->addForeignKey("fk_{$this->translationTable}_content_id__{$this->table}_id", "{{{$this->translationTable}}}", ['[[content_id]]'],
                "{{{$this->table}}}", ['[[id]]'],
                'CASCADE', 'CASCADE');
        }

        public function down()
        {
            $this->dropForeignKey("fk_{$this->translationTable}_content_id__{$this->table}_id", "{{{$this->translationTable}}}");
            $this->dropTable("{{{$this->translationTable}}}");
        }
    }
