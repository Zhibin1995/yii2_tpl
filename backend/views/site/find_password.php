<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = '找回密码';
$this->context->layout = 'none';
?>
<?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>
    <div class="form-horizontal">
        <br>
        <br>
        <br>
        <br>
        <div class="form-group">
            <label class="col-md-offset-3 control-label col-sm-1"></label>
            <div class="col-md-3"><h4>找回密码</h4></div>
        </div>

        <hr/>
        <div class="form-group">
            <label class="col-md-offset-3 control-label col-md-1 col-md-0-6"></label>

            <div class="col-md-3 has-error"  style="height:30px;">
                <span id="message" class="help-block" style="display:none"></span><br>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-offset-3 control-label col-md-1 col-md-0-6" for="username">手机号</label>

            <div class="col-md-3">
                <input class="form-control text-box single-line" type="text" id="mobile"/>
            </div>
            <div class="col-md-2 form-control-static">

            </div>
        </div>

        <div class="form-group">
            <label class="col-md-offset-3 control-label col-md-1 col-md-0-6" for="password">验证码</label>

            <div class="col-md-2" style="width:195px;">
                <input class="form-control text-box single-line password" id="verify-code"/>
            </div>
            <div class="col-md-1" style="padding-left:0px;">
                <a href="javascript:;" id="get-verify-code" class="btn btn-default">获取验证码</a>
            </div>
        </div>
        <br>

        <div class="form-group">
            <label class="col-md-offset-3 control-label col-md-1 col-md-0-6"></label>

            <div class="col-md-1">
                <input type="button" id="submit" value="立即验证" class="btn btn-primary"/>
            </div>
            <div class="col-md-2 form-control-static text-right">
                <a href="/site/login">立即登录</a>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>

<script type="text/javascript">
    var $mobile = $("#mobile");
    var $message = $("#message");
    var $button = $("#submit");
    var $verifyCode=$("#verify-code");
    var $getVerifyCode = $("#get-verify-code");

    function countdown() {
        var sec = parseInt($getVerifyCode.text());
        sec--;
        if (sec == 0) {
            $getVerifyCode.one("click", getVerifyCode);
            $getVerifyCode.text("获取验证码");
        } else {
            $getVerifyCode.text(sec + "秒重新发送");
            setTimeout(countdown, 1000);
        }
    }

    function getVerifyCode() {
        if ($getVerifyCode.hasClass('disabled')) {
            return false;
        }

        $message.text('').hide();
        var mobile = $.trim($mobile.val());
        if (!mobile) {
            $("#mobile").focus();
            $message.text('请输入您的手机号').show();
            $getVerifyCode.one("click", getVerifyCode);
            return false;
        }

        if (!/^1\d{10}$/.test(mobile)) {
            $mobile.focus();
            $message.text('请正确输入您的手机号').show();
            $getVerifyCode.one("click", getVerifyCode);
            return false;
        }

        $getVerifyCode.text('发送中...').addClass('disabled');
        $.post('/site/send-verify-code', {'mobile': mobile}, function (data) {
            $getVerifyCode.removeClass('disabled');
            if (data && data.status == 0) {
                $getVerifyCode.text("60秒重新发送");
                setTimeout(countdown, 1000);
            } else {
                $message.text((data && data.msg) ? data.msg : '提交失败，请稍候再试').show();
                $getVerifyCode.text("获取验证码").one("click", getVerifyCode);
            }
        }, 'json')
    }
    $getVerifyCode.one("click", getVerifyCode);
    function submitUserForm() {
        $message.text('').hide();

        var mobile = $.trim($("#mobile").val());
        if (!mobile) {
            $mobile.focus();
            $message.text('请输入您的手机号').show();
            $("#submit").one("click", submitUserForm);
            return false;
        }

        if (!/^1\d{10}$/.test(mobile)) {
            $mobile.focus();
            $message.text('请正确输入您的手机号').show();
            $("#submit").one("click", submitUserForm);
            return false;
        }

        var verify_code = $.trim($verifyCode.val());
        if (!verify_code) {
            $verifyCode.focus();
            $message.text('请输入短信验证码').show();
            $("#submit").one("click", submitUserForm);
            return false;
        }

        if (!/^\d{6}$/.test(verify_code)) {
            $verifyCode.focus();
            $message.text('验证码错误').show();
            $("#submit").one("click", submitUserForm);
            return false;
        }

        layer.load(2, {shade: false});
        $button.text('提交中...').addClass("submitting");
        $.post('/site/verify-code', {mobile: mobile, code: verify_code}, function (data) {
            layer.closeAll();
            if (data && data.status == 0) {
                window.location.href = '/site/request-reset-password?mobile=' + mobile + '&code=' + verify_code;
            } else {
                $message.text((data && data.msg) ? data.msg : '提交失败，请稍候再试').show();
                $button.text('立即验证').removeClass("submitting");
                $button.one("click", submitUserForm);
            }
        }, 'json');
        return false;
    }
    $button.one("click", submitUserForm);

    $('#mobile,#verify-code').bind('blur', function () {
        if ($(this).val()) {
            $message.text('').hide();
        }
    });
</script>
