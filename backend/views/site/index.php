<?php

/* @var $user User */
/* @var $this \yii\web\View */

use app\models\User;
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <title><?=Yii::$app->name?></title>

    <meta name="keywords" content="<?=Yii::$app->name?>">
    <meta name="description" content="<?=Yii::$app->name?>">

    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html"/>
    <![endif]-->

    <link rel="shortcut icon" href="favicon.ico">
    <link rel="stylesheet" href="/styles/bootstrap.min.css?v=3.3.6">
    <link rel="stylesheet" href="/styles/font-awesome.min.css?v=4.4.0">
    <link rel="stylesheet" href="/styles/plugins/toastr/toastr.min.css"/>
    <link rel="stylesheet" href="/styles/animate.min.css">
    <link rel="stylesheet" href="/styles/style.min.css?v=4.1.0">
    <script type="text/javascript">
        if (window.top != window) {
            window.top.location.href = window.location.href;
        }
    </script>
</head>

<body class="fixed-sidebar full-height-layout gray-bg  pace-done fixed-nav" style="overflow:hidden">
<div id="wrapper">
    <a id="openTab" class="J_menuItem" href="javascript:;" style="display:none;"></a>
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="nav-close"><i class="fa fa-times-circle"></i>
        </div>
        <div class="nav-header">
            <a href="javascript:;" class="navbar-minimalize">
                <i class="fa fa-bars"></i>
                <span class="nav-label">导航菜单</span>
                <span class="fa fa-angle-double-left "></span>
            </a>
        </div>
        <div class="sidebar-collapse">
            <?= $this->render('@jeemoo/rbac/views/menu/_menu', ['items' => \jeemoo\rbac\models\Menu::getUserMenus(), 'parent' => null]) ?>
        </div>
    </nav>
    <div id="page-wrapper" class="gray-bg dashbard-1">
        <div class="row border-bottom">
            <nav class="navbar navbar-fixed-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <h2><?=Yii::$app->name?></h2>
                </div>
                <ul class="nav navbar-top-links navbar-right">
                    <li class="hidden-xs">
                        <a title="个人资料" href="/personal" class="J_menuItem">
                            <i class="fa fa-tasks"></i> <?= $user->username ?>
                        </a>
                    </li>
                    <li class="hidden-xs">
                        <a href="/personal/logout">
                            <i class="fa fa-sign-out"></i> 退出
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="row content-tabs">
            <button class="roll-nav roll-left J_tabLeft"><i class="fa fa-backward"></i>
            </button>
            <nav class="page-tabs J_menuTabs">
                <div class="page-tabs-content">
                    <a href="javascript:;" class="active J_menuTab" data-id="/site/home">首页</a>
                </div>
            </nav>
            <button class="roll-nav roll-right J_tabRight"><i class="fa fa-forward"></i>
            </button>
            <div class="btn-group roll-nav roll-right">
                <button class="dropdown J_tabClose" data-toggle="dropdown">关闭操作<span class="caret"></span>

                </button>
                <ul role="menu" class="dropdown-menu dropdown-menu-right">
                    <li class="J_tabShowActive"><a>定位当前选项卡</a>
                    </li>
                    <li class="divider"></li>
                    <li class="J_tabCloseAll"><a>关闭全部选项卡</a>
                    </li>
                    <li class="J_tabCloseOther"><a>关闭其他选项卡</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row J_mainContent" id="content-main">
            <iframe class="J_iframe" name="iframe0" width="100%" height="100%" src="/site/home" frameborder="0" data-id="/site/home" seamless></iframe>
        </div>
        <div class="footer">
            <div class="pull-right">Copyright 2018 @ <a href="javascript:void(0)" target="_blank">Jeemoo</a></div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/scripts/jquery.min.js?v=2.1.4"></script>
<script type="text/javascript" src="/scripts/bootstrap.min.js?v=3.3.6"></script>
<script type="text/javascript" src="/scripts/plugins/toastr/toastr.min.js"></script>
<script type="text/javascript" src="/scripts/plugins/metisMenu/jquery.metisMenu.js"></script>
<script type="text/javascript" src="/scripts/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script type="text/javascript" src="/scripts/plugins/layer/layer.min.js"></script>
<script type="text/javascript" src="/scripts/hplus.min.js?v=4.1.0"></script>
<script type="text/javascript" src="/scripts/contabs.min.js"></script>
<script type="text/javascript" src="/scripts/plugins/pace/pace.min.js"></script>
</body>
</html>
