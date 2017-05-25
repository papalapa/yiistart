<?php

    use yii\db\Migration;

    class m170525_103015_create_partners_translation_table extends Migration
    {
        public $table            = 'partners';
        public $translationTable = 'partners_translation';

        public function up()
        {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
            $this->createTable("{{{$this->translationTable}}}", [
                'id'         => $this->primaryKey(),
                'language'   => $this->string(16)->notNull(),
                'content_id' => $this->integer()->unsigned()->notNull(),
                'alt'        => $this->string(128)->defaultValue(null),
                'title'      => $this->string(128)->defaultValue(null),
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
