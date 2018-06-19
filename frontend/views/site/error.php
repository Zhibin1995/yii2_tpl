<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = '服务器内部错误';
?>
<br><br>
<br><br>
<div class="weui-msg">
    <div class="weui-msg__icon-area"><i class="weui-icon-warn weui-icon_msg"></i></div>
    <div class="weui-msg__text-area">
        <p class="weui-msg__title"><?= $message ? nl2br(Html::encode($message)) : '服务器内部错误' ?></p>
    </div>
    <div class="weui-msg__extra-area">
        <div class="weui-footer">
            <p class="weui-footer__text">Copyright © 2017 Cuisinetalk.cn</p>
        </div>
    </div>
</div>
