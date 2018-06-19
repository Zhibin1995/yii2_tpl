<?php
$this->context->layout = 'none';
$this->title = "雄安管理系统";
?>
<style type="text/css">
    h1 {
        text-align: center;
        font-size: 36px;
        margin-top: 80px;
        color: #fff;
        text-transform: uppercase;
        letter-spacing: 5px;
    }
    .main-agileinfo{
        margin:10% auto 13%;
        width:44%;
    }
    .main-agileinfo h2{
        color:#fff;
        font-size:24px;
        margin-bottom:30px;
    }
    input[type="text"],input[type="password"]{
        width:100%;
        padding: 15px 45px 15px 10px;
        background: rgba(255, 255, 255, 0.15);
        background:#fff;
        border:none;
        outline:none;
        font-size:14px;
        margin-bottom:20px;
        background: rgb(255, 255, 255) url("../images/user.png") no-repeat 412px 11px;
    }
    input[type="password"]{
        background: rgb(255, 255, 255) url("../images/password.png") no-repeat 412px 11px;
    }

    input[type="submit"]{
        background:#1ab394;
        padding:13px 0;
        font-size:16px;
        color:#fff;
        cursor:pointer;
        outline:none;
        transition: 0.5s all;
        -webkit-transition: 0.5s all;
        -moz-transition: 0.5s all;
        -o-transition: 0.5s all;
        -ms-transition: 0.5s all;
        width:100%;
        border:none;
        margin-top:25px;
    }

    input[type="submit"]:hover{
        background:#337ab7;
    }
    .footer-w3l p{
        font-size:14px;
        line-height:25px;
        color:#fff;
        text-align:center;
        margin-bottom:15px;
    }
    .footer-w3l p a{
        color:#fff;
    }
    .footer-w3l p a:hover{
        text-decoration:underline;
    }
</style>
<link rel="stylesheet" href="/styles/supersized.css">
<h1>雄安生活管理系统</h1>
<div class="main-agileinfo">
    <h2>立即登录</h2>
    <form method="post">
        <input name="User[mobile]" id="username" value="<?=$model->mobile ?>" placeholder="登录账号" type="text">
        <input name="User[password]" id="password" value="<?=$model->password ?>" placeholder="密码" type="password">
        <span id="message" class="help-block" style="margin-left:0px; color:#fff;">
             <?=$model->hasErrors()?current($model->getFirstErrors()):""?>
         </span>
        <input name="_csrf-backend" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
        <div class="clear"></div>
        <input class="btn btn-primary" id="login" value="登 录" type="submit">
    </form>
</div>
<div class="footer-w3l">
    <p class="agile"> &copy; <?=date('Y')?> 雄安生活管理系统</a></p>
</div>
<script src="/scripts/jquery.min.js"></script>
<script src="/scripts/supersized.3.2.7.min.js"></script>
<script src="/scripts/supersized-init.js"></script>
<script type="text/javascript">
    $("#login").bind('click', function () {
        $("#message").text('').hide();
        if (!$("#username").val()) {
            $("#message").text('请输入登录帐号').show();
            $("#username").get(0).focus();
            return false;
        }
        if (!$("#password").val()) {
            $("#message").text('请输入登录密码').show();
            $("#password").get(0).focus();
            return false;
        }
        return true;
    });
    $('#username,#password').bind('blur', function () {
        if ($(this).val()) {
            $("#message").text('').hide();
        }
    })
</script>
