<?php
    /**
     * This view is used by console/controllers/MigrateController.php
     * The following variables are available in this view:
     */
    /* @var $className string the new migration class name without namespace */
    /* @var $namespace string the new migration class namespace */

    echo "<?php\n";
    if (!empty($namespace)) {
        echo "\nnamespace {$namespace};\n";
    }
?>

use yii\db\Migration;

class <?= $className ?> extends Migration
{
public $table = '';
public $translationTable = '';
public function up()
{
$tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
$this->createTable("{{$translationTable}}", [
'id' => $this->primaryKey(),
'language' => $this->string(16)->notNull(),
'content_id' => $this->integer()->unsigned()->notNull(),
'' => $this->string(),
], $tableOptions);

$this->alterColumn('{{$translationTable}}', '[[id]]', 'INT UNSIGNED NOT NULL AUTO_INCREMENT');
$this->createIndex("idx_$translationTable_content_id", "{{$translationTable}}", ['[[content_id]]']);
$this->createIndex("idx_$translationTable_language", "{{$translationTable}}", ['[[language]]']);
$this->createIndex("idx_$translationTable_language_content_id", "{{$translationTable}}", ['[[language]]', '[[content_id]]'], true);
$this->addForeignKey('fk_$translationTable_content_id__pages_id', "{{$translationTable}}", ['[[content_id]]'],
"{{$table}}", ['[[id]]'],
'CASCADE', 'CASCADE');
}

public function down()
{
$this->dropForeignKey("fk_$translationTable_content_id__$table_id", "{{$translationTable}}");
$this->dropTable("{{$translationTable}}");
}
}
