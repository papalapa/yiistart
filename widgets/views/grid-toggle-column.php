<?php
    /* @var $model ActiveRecord */
    /* @var $attribute string */
    /* @var $value boolean */

    use papalapa\yiistart\helpers\StringHelper;
    use papalapa\yiistart\widgets\ToastrAlert;
    use yii\db\ActiveRecord;
    use yii\helpers\Url;

    echo ToastrAlert::widget();
?>
<div class="pjax-toggle-attribute-<?= $attribute ?>-handler" id="pjax-toggle-attribute-<?= $attribute ?>-<?= StringHelper::translate($model->primaryKey) ?>" data-pjax-container="">
    <? if (is_null($model->getAttribute($attribute))) : ?>
        <a class="inline-block" href="<?= Url::to(['toggle', 'id' => $model->primaryKey, 'attribute' => $attribute, 'value' => 0]) ?>">
            <i class="fa fa-toggle-off text-danger"></i>
        </a>
        <a class="inline-block" href="<?= Url::to(['toggle', 'id' => $model->primaryKey, 'attribute' => $attribute, 'value' => 1]) ?>">
            <i class="fa fa-toggle-off fa-flip-horizontal text-success"></i>
        </a>
    <? else : ?>
        <i class="<?= $model->getAttribute($attribute) ? 'fa fa-check text-success' : 'fa fa-times-circle text-danger' ?>"></i>
        <a href="<?= Url::to(['toggle', 'id' => $model->primaryKey, 'attribute' => $attribute]) ?>" title="<?= $model->getAttribute($attribute) ? 'Выключить' : 'Включить' ?>"
           class="inline-block <?= is_null($model->getAttribute($attribute)) ? '' : ($model->getAttribute($attribute) ? 'text-success' : 'text-danger') ?>">
            <i class="<?= is_null($model->getAttribute($attribute)) ? 'fa fa-toggle-off' : 'fa fa-toggle-on '.($model->getAttribute($attribute) ? '' : 'fa-flip-horizontal') ?>"></i>
        </a>
    <? endif; ?>
</div>
