<?php

    /* @var $this \yii\web\View */
    /* @var $content string */
    /* @var $menu array - Site menu*/
    /* @var $general array - General menu */

    use papalapa\yiistart\assets\AppAsset;
    use papalapa\yiistart\modules\users\models\BaseUser;
    use papalapa\yiistart\widgets\ToastrAlert;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\Breadcrumbs;

    yiister\gentelella\assets\Asset::register($this);
    AppAsset::register($this);

?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta charset="<?= Yii::$app->charset ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="nav-md">
    <?php $this->beginBody(); ?>

    <div class="container body">
        <div class="main_container">

            <!-- sidebar -->
            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">
                    <div class="navbar nav_title" style="border: 0;">
                        <a href="<?= Url::home() ?>" title="Сделано в Instinct" class="site_title">
                            <!--<i class="fa fa-linkedin"></i>-->
                            <span><img src="<?= Yii::$app->assetManager->getBundle(AppAsset::className())->baseUrl ?>/image/logo_light.png"></span>
                        </a>
                    </div>
                    <div class="clearfix"></div>
                    <br />
                    <!-- sidebar menu -->
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                        <div class="menu_section">
                            <h3>По сайту:</h3>
                            <?= \yiister\gentelella\widgets\Menu::widget($menu) ?>
                        </div>
                        <div class="menu_section">
                            <h3>Основные:</h3>
                            <?= \yiister\gentelella\widgets\Menu::widget($general) ?>
                        </div>
                    </div>
                    <!-- /sidebar menu -->
                </div>
            </div>
            <!-- /sidebar -->

            <!-- top navigation -->
            <div class="top_nav">
                <div class="nav_menu">
                    <nav>
                        <div class="nav toggle" style="width: auto">
                            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                        </div>
                        <!--<ul class="nav navbar-nav">
                            <li>
                                <a href="http://instinct.kz/" title="Перейти на сайт Инстинкта">
                                    <img src="<? /*= Yii::$app->assetManager->getBundle(AppAsset::className())->baseUrl */ ?>/image/logo.png">
                                </a>
                            </li>
                        </ul>-->
                        <ul class="nav navbar-nav navbar-right">
                            <li>
                                <a href="javascript:void(0)" class="user-profile dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-user-secret"></i> <b><?= Yii::$app->user->identity->email ?></b>
                                    <span class=" fa fa-angle-down"></span>
                                </a>
                                <ul class="dropdown-menu dropdown-usermenu pull-right">
                                    <? if (Yii::$app->user->identity->role == BaseUser::ROLE_DEVELOPER): ?>
                                        <li>
                                            <a title="Gii code generator" href="<?= Url::to(['/gii']) ?>">
                                                <i class="fa fa-fw fa-code pull-right"></i> Gii генератор
                                            </a>
                                        </li>
                                        <li>
                                            <a title="Yii webshell" href="<?= Url::to(['/shell']) ?>">
                                                <i class="fa fa-fw fa-terminal pull-right"></i> Yii терминал
                                            </a>
                                        </li>
                                    <? endif; ?>
                                    <li>
                                        <a title="Сменить пароль" href="<?= Url::to(['/user/secure']) ?>">
                                            <i class="fa fa-fw fa-lock pull-right"></i> Сменить пароль
                                        </a>
                                    </li>
                                    <li>
                                        <a data-toggle="tooltip" data-placement="top" title="Выйти" href="<?= Url::to(['/user/identity/logout']) ?>"
                                           data-method="post" data-confirm="Вы уверены, что хотите выйти?">
                                            <i class="fa fa-fw fa-sign-out pull-right"></i> Выйти
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- /top navigation -->

            <!-- page content -->
            <div class="right_col" style="background: white">
                <div class="clearfix" style="margin-bottom: 10px;"></div>
                <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
                <?= ToastrAlert::widget() ?>
                <?= $content ?>
                <div class="clearfix"></div>
            </div>
            <!-- /page content -->

            <!-- footer content -->
            <footer style="padding: 30px 20px">
                <div>Developed with <a href="http://instinct.kz" rel="nofollow" target="_blank">Instinct</a></div>
            </footer>
            <!-- /footer content -->

        </div>
    </div>

    <!-- footer content -->
    <div id="custom_notifications" class="custom-notifications dsp_none">
        <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group"></ul>
        <div class="clearfix"></div>
        <div id="notif-group" class="tabbed_notifications"></div>
    </div>
    <!-- /footer content -->

    <?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>
