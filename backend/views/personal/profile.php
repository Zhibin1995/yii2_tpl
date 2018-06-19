<?php

/* @var $model User */
/* @var $this \yii\web\View */

use jeemoo\rbac\models\Menu;
use common\models\User;
?>
<div class="form-horizontal ajax-form record">
    <?php $form = yii\bootstrap\ActiveForm::begin(); ?>
    <div class="form-group field-user-mobile required">
        <label class="control-label col-xs-3" for="user-mobile">手机</label>
        <div class="col-xs-8">
            <input id="mobile" class="form-control" name="User[mobile]" data-mobile="<?=$model->mobile ?>" value="<?=$model->mobile ?>" type="text">
            <p class="help-block help-block-error"></p></div>
    </div>
    <div class="form-group required verify-code" style="display:none;">
        <label class="control-label col-xs-3" >验证码</label>
        <div class="col-xs-5" style="width:195px;">
            <input id="verify-code" name="User{verify_code]" class="form-control text-box single-line" />
        </div>
        <div class="col-xs-3" style="width:195px;">
            <a href="javascript:;" id="get-verify-code" class="btn">获取验证码</a>
        </div>
    </div>
    <?= $form->field($model, 'username')->textInput() ?>

    <?= $form->field($model, 'email')->textInput() ?>


    <div class="form-group  fixed_full record-footer">
        <div class="col-xs-offset-3 col-md-8">
            <?= yii\helpers\Html::submitButton('确认修改', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
        </div>
    </div>
    <?php yii\bootstrap\ActiveForm::end(); ?>
</div>
<script type="text/javascript">
    var  $mobile=$("#mobile");
    var $getVerifyCode=$("#get-verify-code");

    $mobile.bind("change", function () {
        $(this).val($(this).val()).trigger('keyup');
    }).bind("focus", function () {
        if ($(this).val() == $(this).data("mobile")) {
            $(this).val('');
        }
    }).bind("blur", function () {
        if (!$(this).val()) {
            $(this).val($(this).data("mobile"));
        }
        $(this).trigger('keyup');
    }).bind("keyup", function () {
        if ($(this).val()) {
            if ($(this).val() != $(this).data("mobile")) {
                $(".verify-code").show();
            } else {
                $(".verify-code").hide();
            }
        }
    });
    function countdown() {
        var sec = parseInt($getVerifyCode.text());
        sec--;
        if (sec == 0) {
            $getVerifyCode.one("click", getVerifyCode);
            $getVerifyCode.text("获取验证码");
        } else {
            $getVerifyCode.text(sec + "秒重新发送")
            setTimeout(countdown, 1000);
        }
    }

    function getVerifyCode() {
        if ($getVerifyCode.hasClass('disabled')) {
            return false;
        }
        var mobile = $.trim($mobile.val());
        if (!mobile) {
            $("#mobile").focus();
            error('请输入手机号');
            $getVerifyCode.one("click", getVerifyCode);
            return false;
        }

        if (!/^1\d{10}$/.test(mobile)) {
            $("#mobile").focus();
            error('请正确输入手机号');
            $getVerifyCode.one("click", getVerifyCode);
            return false;
        }

        $getVerifyCode.text('发送中...').addClass('disabled');
        $.post('/admin/personal/get-verify-code', {'mobile': mobile}, function (data) {
            $getVerifyCode.removeClass('disabled');
            if (data && data.status == 0) {
                $getVerifyCode.text("60秒重新发送");
                setTimeout(countdown, 1000);
            } else {
                error(data && data.msg ? data.msg : '提交失败，请稍候再试');
                $getVerifyCode.text("获取验证码").one("click", getVerifyCode);
            }
        }, 'json')
    }
    $getVerifyCode.one("click", getVerifyCode);
</script>