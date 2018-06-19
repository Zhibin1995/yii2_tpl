<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use backend\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
<head>
    <title><?=Yii::$app->name?></title>
    <?= Html::csrfMetaTags() ?>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/styles/font-awesome.min.css?v=4.4.0">
    <link rel="stylesheet" href="/styles/cropper.min.css"/>
    <link rel="stylesheet" href="/styles/plugins/iCheck/custom.css">
    <link rel="stylesheet" href="/styles/plugins/chosen/chosen.css">
    <link rel="stylesheet" href="/styles/plugins/clockpicker/clockpicker.css" >
	<link rel="stylesheet" href="/styles/jquery.fixedheadertable.min.css" >
    <?php $this->head() ?>
    <link rel="stylesheet" type="text/css" media="screen" href="/scripts/plugins/selectivity/stylesheets/selectivity-jquery.css">
    <script src="/scripts/plugins/selectivity/javascripts/selectivity-jquery.js"></script>
    <script type="text/javascript" src="/scripts/cropper.min.js"></script>
    <script type="text/javascript" src="/scripts/jquery.form.min.js"></script>
    <script type="text/javascript" src="/scripts/plugins/layer/layer.min.js"></script>
    <script type="text/javascript" src="/scripts/plugins/layer/laydate/laydate.js"></script>
    <script type="text/javascript" src="/scripts/plugins/iCheck/icheck.min.js"></script>
    <script type="text/javascript" src="/scripts/plugins/chosen/chosen.jquery.js"></script>
    <script type="text/javascript" src="/scripts/jquery.dragsort.min.js"></script>
    <script type="text/javascript" src="/scripts/plugins/ueditor/ueditor.config.js"></script>
    <script type="text/javascript" src="/scripts/plugins/ueditor/ueditor.all.min.js"></script>
	<script type="text/javascript" src="/scripts/jquery.fixedheadertable.min.js"></script>
    <script type="text/javascript">
        if (window.top == window) {
            //window.top.location.href = '/';
        }
        $(function(){
            $("input").iCheck({checkboxClass: "icheckbox_square-green", radioClass: "iradio_square-green"});
        })
    </script>
</head>
<body class="white-bg">
<script id="img-edit-template" type="text/html">
    <div class="edit">
        <div class="img-container"></div>
        <div class="command text-center" style="padding-top:5px;">
            <a id="confirm-cut-img" class="btn btn-primary">确定</a>
        </div>
    </div>
</script>
<?php $this->beginBody() ?>
<div id="content" class="wrapper wrapper-content" style="overflow-x: auto">
    <?= $content ?>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>