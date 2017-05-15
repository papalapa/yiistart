<?php

    use yii\db\Migration;

    /**
     * Handles adding alias to table `elements`.
     */
    class m170515_113801_add_alias_column_to_elements_table extends Migration
    {
        /**
         * @inheritdoc
         */
        public function up()
        {
            $this->addColumn('{{elements}}', '[[alias]]', $this->string(64)->defaultValue(null) . ' AFTER [[id]]');
            $this->createIndex('uidx_elements__alias', '{{elements}}', ['[[alias]]'], true);
        }

        /**
         * @inheritdoc
         */
        public function down()
        {
            $this->dropColumn('{{elements}}', '[[alias]]');
        }
    }
