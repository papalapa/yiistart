<?php
    /**
     * This is the template for generating a CRUD controller class file.
     */

    use yii\db\ActiveRecordInterface;
    use yii\helpers\StringHelper;

    /* @var $this yii\web\View */
    /* @var $generator yii\gii\generators\crud\Generator */

    $controllerClass  = StringHelper::basename($generator->controllerClass);
    $modelClass       = StringHelper::basename($generator->modelClass);
    $searchModelClass = StringHelper::basename($generator->searchModelClass);
    if ($modelClass === $searchModelClass) {
        $searchModelAlias = $searchModelClass.'Search';
    }

    /* @var $class ActiveRecordInterface */
    $class               = $generator->modelClass;
    $pks                 = $class::primaryKey();
    $urlParams           = $generator->generateUrlParams();
    $actionParams        = $generator->generateActionParams();
    $actionParamComments = $generator->generateActionParamComments();

    echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>;

use <?= ltrim($generator->modelClass, '\\') ?>;
<?php if (!empty($generator->searchModelClass)): ?>
    use <?= ltrim($generator->searchModelClass, '\\').(isset($searchModelAlias) ? " as $searchModelAlias" : "") ?>;
<?php else: ?>
    use yii\data\ActiveDataProvider;
<?php endif; ?>
use <?= ltrim($generator->baseControllerClass, '\\') ?>;

/**
* <?= $controllerClass ?> implements the CRUD actions for <?= $modelClass ?> model.
*/
class <?= $controllerClass ?> extends <?= StringHelper::basename($generator->baseControllerClass)."\n" ?>
{
    /**
    * @var array
    */
    protected $permissions = [
        // TODO: put rules
        'create' => 'createContent',
        'view'   => 'viewContent',
        'update' => 'updateContent',
        'index'  => 'indexContent',
        'delete' => 'deleteContent',
    ];

    /**
    * @param \yii\base\Action $action
    * @return bool
    */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $this->model       = <?= StringHelper::basename($generator->modelClass) ?>::className();
            $this->searchModel = <?= !empty($generator->searchModelClass) ? StringHelper::basename($generator->searchModelClass) : null ?>::className();

            return true;
        }

        return false;
    }
}
