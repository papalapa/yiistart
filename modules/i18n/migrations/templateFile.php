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
$this->createTable("{{{$this->translationTable}}}", [
'id' => $this->primaryKey(),
'language' => $this->string(16)->notNull(),
'content_id' => $this->integer()->unsigned()->notNull(),
'' => $this->string(),
], $tableOptions);

$this->alterColumn('{{{$this->translationTable}}}', '[[id]]', 'INT UNSIGNED NOT NULL AUTO_INCREMENT');
$this->createIndex("idx_{$this->translationTable}_content_id", "{{{$this->translationTable}}}", ['[[content_id]]']);
$this->createIndex("idx_{$this->translationTable}_language", "{{{$this->translationTable}}}", ['[[language]]']);
$this->createIndex("idx_{$this->translationTable}_language_content_id", "{{{$this->translationTable}}}", ['[[language]]', '[[content_id]]'], true);
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
