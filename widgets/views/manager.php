<?php

    /* @var $this \yii\web\View */

?>
<h1 class="page-header"><i class="fa fa-fw fa-hdd-o"></i> Файловый менеджер</h1>
<?
    echo \mihaildev\elfinder\ElFinder::widget([
        'language'         => 'ru',
        'controller'       => 'manager',
        'filter'           => [],
        'containerOptions' => ['style' => ['height' => '700px']],
    ]);
?>
