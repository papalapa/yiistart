<?php

    namespace papalapa\yiistart\migrations;

    use yii\db\Migration;

    /**
     * Class m160719_061847_create_table_tracking
     * @package papalapa\yiistart\migrations
     */
    class m160719_061847_create_table_tracking extends Migration
    {
        public function up()
        {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=MyISAM';
            $this->createTable('{{tracking}}', [
                'model_name' => $this->string(64),
                'model_pk'   => $this->string(64),
                'time_at'    => $this->timestamp(),
                'date_at'    => $this->date(),
            ], $tableOptions);

            $this->addPrimaryKey('entity', '{{tracking}}', ['[[model_name]]', '[[model_pk]]', '[[time_at]]']);
            $this->createIndex('entities_in_date', '{{tracking}}', ['[[model_name]]', '[[model_pk]]', '[[date_at]]']);

            $this->db->createCommand("
                CREATE TRIGGER [[insert_into_tracking]] BEFORE INSERT ON {{tracking}} FOR EACH ROW
                BEGIN
                    SET NEW.[[time_at]] = NOW(), NEW.[[date_at]] = CURRENT_DATE();
                END
            ")->execute();
        }

        public function down()
        {
            $this->db->createCommand("
                DROP TRIGGER IF EXISTS {{tracking}}.[[insert_into_tracking]];
            ")->execute();
            $this->dropTable('{{tracking}}');
        }
    }
