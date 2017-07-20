<?php

    namespace papalapa\yiistart\modules\settings\migrations;

    use yii\db\Migration;

    /**
     * Class m172007_135743_add_comment_column_on_settings_table
     * @package papalapa\yiistart\modules\settings\migrations
     */
    class m172007_135743_add_comment_column_on_settings_table extends Migration
    {
        public function safeUp()
        {
            $this->addColumn('settings', 'comment', $this->string(1024)->defaultValue(null).' AFTER [[value]]');
        }

        public function safeDown()
        {
            $this->dropColumn('settings', 'comment');
        }
    }
