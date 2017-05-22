<?php

    namespace papalapa\yiistart\modules\subscribe\migrations;

    use yii\db\Migration;

    /**
     * Class m170515_150621_create_pivot_dispatches_subscribers_table
     * @package papalapa\yiistart\modules\subscribe\migrations
     */
    class m170515_150621_create_pivot_dispatches_subscribers_table extends Migration
    {
        /**
         * @inheritdoc
         */
        public function up()
        {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
            $this->createTable('{{pivot_dispatches_subscribers}}', [
                'id'            => $this->primaryKey(),
                'dispatch_id'   => $this->integer()->unsigned()->notNull(),
                'subscriber_id' => $this->integer()->unsigned()->notNull(),
                'status'        => $this->smallInteger()->unsigned()->notNull()->defaultValue(0),
            ], $tableOptions);

            $this->alterColumn('{{pivot_dispatches_subscribers}}', '[[id]]', 'INT UNSIGNED NOT NULL AUTO_INCREMENT');
            $this->addForeignKey('fk_dispatches_subscribers__dispatches__id',
                '{{pivot_dispatches_subscribers}}', ['[[dispatch_id]]'],
                '{{dispatches}}', ['[[id]]'], 'CASCADE', 'CASCADE');
            $this->addForeignKey('fk_dispatches_subscribers__subscribers__id',
                '{{pivot_dispatches_subscribers}}', ['[[subscriber_id]]'],
                '{{subscribers}}', ['[[id]]'], 'CASCADE', 'CASCADE');
        }

        /**
         * @inheritdoc
         */
        public function down()
        {
            $this->dropForeignKey('fk_dispatches_subscribers__dispatches__id', '{{pivot_dispatches_subscribers}}');
            $this->dropForeignKey('fk_dispatches_subscribers__subscribers__id', '{{pivot_dispatches_subscribers}}');
            $this->dropTable('{{pivot_dispatches_subscribers}}');
        }
    }
