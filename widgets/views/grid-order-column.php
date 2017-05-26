<?php
    /* @var $model ActiveRecord */
    /* @var $attribute string */
    /* @var $value boolean */

    use papalapa\yiistart\widgets\ToastrAlert;
    use yii\db\ActiveRecord;
    use yii\helpers\Url;

    echo ToastrAlert::widget();
?>
<div class="pjax-reorder-attribute-<?= $attribute ?>-handler" id="pjax-reorder-attribute-<?= $attribute ?>-<?= $model->primaryKey ?>" data-pjax-container="">
    <a href="<?= Url::to(['reorder', 'id' => $model->primaryKey, 'attribute' => $attribute, 'direction' => -1]) ?>"
       class="label label-danger">▼</a>
    <? if (!is_null($model->getAttribute($attribute))) : ?>
        <span class="label label-primary"><?= $model->getAttribute($attribute) ?></span>
    <? endif; ?>
    <a href="<?= Url::to(['reorder', 'id' => $model->primaryKey, 'attribute' => $attribute, 'direction' => 1]) ?>"
       class="label label-success">▲</a>
</div>
