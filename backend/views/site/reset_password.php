<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $mobile string */
/* @var $code string */
/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = '重置密码';
$this->context->layout = 'none';
?>
<form method="post">
    <br>
    <br>
    <br>
    <br>

    <div class="form-horizontal">
        <div class="form-group">
            <label class="col-md-offset-3 control-label col-sm-1"></label>

            <div class="col-md-3"><h4>重置密码</h4></div>
        </div>

        <hr/>
        <br>

        <div class="form-group">
            <label class="col-md-offset-3 control-label col-sm-1"></label>

            <div class="col-md-3 has-error"  style="height:30px;">
                <span id="message" class="help-block" style="display:none"></span><br>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-offset-3 control-label col-sm-1" for="Title">手机</label>

            <div class="col-md-3 form-control-static">
               <?=$mobile?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-offset-3 control-label col-sm-1" for="Title">新密码</label>

            <div class="col-md-3">
                <input class="form-control text-box single-line" type="password" id="password"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-offset-3 control-label col-sm-1" for="Title">确认密码</label>

            <div class="col-md-3">
                <input class="form-control text-box single-line" type="password" id="confirm_password"/>
            </div>
        </div>
        <br>

        <div class="form-group">
            <label class="col-md-offset-3 control-label col-sm-1"></label>

            <div class="col-md-3">
                <a href="javascript:;" id="submit" class="btn btn-primary">确认提交</a>
            </div>
        </div>
    </div>
</form>
<script type="text/javascript">
    var $message = $("#message");
    var $password = $("#password");
    var $confirmPassword = $("#confirm_password");
    var $button = $("#submit");
    function submitUserForm() {
        $message.text('').hide();
        var password = $.trim($password.val());
        if (!password) {
            $password.focus();
            $message.text('请输入您的新密码').show();
            $("#submit").one("click", submitUserForm);
            return false;
        }

        var confirm_password = $.trim($confirmPassword.val());
        if (!confirm_password) {
            $("#confirm_password").focus();
            $message.text('请输入确认密码').show();
            $("#submit").one("click", submitUserForm);
            return false;
        }

        if (password != confirm_password) {
            $("#confirm_password").focus();
            $message.text('确认密码错误').show();
            $("#submit").one("click", submitUserForm);
            return false;
        }

        layer.load(2, {shade: false})
        $button.text('提交中...').addClass("submitting");
        $.post('/site/reset-password?mobile=<?=$mobile?>&code=<?=$code?>', {
            password: password,
            confirm_password: confirm_password
        }, function (data) {
            layer.closeAll();
            if (data && data.status == 0) {
                layer.msg('设置成功');
                setTimeout(function () {
                    window.location.href = '/site/login';
                }, 2000)
            } else {
                $message.text((data && data.msg) ? data.msg : '提交失败，请稍候再试').show();
                $button.text('确认提交').removeClass("submitting");
                $button.one("click", submitUserForm);
            }
        }, 'json');
    }
    $button.one("click", submitUserForm);

    $('#password,#confirm_password').bind('blur', function () {
        if ($(this).val()) {
            $("#message").text('').hide();
        }
    })
</script>
