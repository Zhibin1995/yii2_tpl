<?php

/* @var $model User */
/* @var $this \yii\web\View */

use jeemoo\rbac\models\Menu;
use app\models\User;
?>
<br><br><br><br>
<div class="form-horizontal col-xs-offset-1 col-xs-10">
    <?php $form = yii\bootstrap\ActiveForm::begin(['id' => 'login-form']); ?>
    <div class="form-group">
        <div class="col-xs-1 form-control-static text-left"><strong>手机号</strong></div>
        <div class="col-xs-7 form-control-static">
            <strong><?= $model->mobile ?></strong>
        </div>
        <div class="col-xs-4 form-control-static text-right">
            <a class="dialog" href="/personal/password"><strong>修改密码</strong></a>
        </div>
    </div>
    <hr>
    <div class="form-group">
        <div class="col-xs-1 form-control-static text-left"><strong>角色</strong></div>

        <div class="col-xs-7 form-control-static">
            <strong><?=\jeemoo\rbac\models\UserRole::getRoleNames($model)?></strong>
        </div>
    </div>
    <hr>
    <div class="form-group">
        <div class="col-xs-1 form-control-static text-left"><strong>最后登录</strong></div>

        <div class="col-xs-7 form-control-static">
            <strong><?= date('Y-m-d H:i', $model->last_login_at) ?></strong>
        </div>
    </div>
    <hr>
    <div class="form-group">
        <div class="col-xs-1 form-control-static text-left"><strong>登录IP</strong></div>

        <div class="col-xs-7 form-control-static">
            <strong><?= $model->last_login_ip ?></strong>
        </div>
    </div>
    <hr>
    <div class="form-group">
        <div class="col-xs-1 form-control-static text-left"><strong>姓名</strong></div>

        <div class="col-xs-7 form-control-static">
            <strong><?= $model->username ?></strong>
        </div>
        <div class="col-xs-4 form-control-static text-right">
        </div>
    </div>
    <hr>
    <div class="form-group">
        <label class="col-xs-1 form-control-static text-left"><strong>邮箱</strong></label>

        <div class="col-xs-7 form-control-static">
            <strong><?= $model->email ?>
        </div>
        <div class="col-xs-4 form-control-static text-right">
            <a class="dialog" href="/personal/profile"><strong>修改资料</strong></a>
        </div>
    </div>
    <hr>
    <?php yii\bootstrap\ActiveForm::end(); ?>
</div>
