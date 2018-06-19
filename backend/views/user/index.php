<?php

use yii\data\Pagination;
use common\models\User;
use jeemoo\rbac\common\HtmlUtils;

/* @var $this yii\web\View */
/* @var $model User */
/* @var $items User[] */
/* @var $pager Pagination */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = \yii\widgets\ActiveForm::begin(); ?>
<div class="form-horizontal list">
    <div class="fixed_full list-header">
        <div class="form-group">
            <div class="col-md-2">
                <?= \yii\bootstrap\Html::activeTextInput($model, 'username', ['class' => 'form-control', 'placeholder' => '姓名']) ?>
            </div>
            <div class="col-md-2">
                <?= \yii\bootstrap\Html::activeTextInput($model, 'mobile', ['class' => 'form-control', 'placeholder' => '手机']) ?>
            </div>
            <div class="col-md-1">
                <?= \yii\helpers\Html::submitButton('查询', ['class' => 'btn btn-primary ajax-refresh']) ?>
            </div>
            <div class="col-md-1">
                <a href="/user/create" class="btn btn-white dialog" title="添加用户" data-height="500">添加</a>
            </div>
        </div>
        <table class="table">
            <tr>
                <th width="120">姓名</th>
                <th width="120">手机</th>
                <th>角色</th>
                <th width="200">邮箱</th>
                <th width="150">最后登录</th>
                <th width="150">登录IP</th>
                <th width="150">添加时间</th>
                <th width="200">操作</th>
            </tr>
        </table>
    </div>

    <table class="table table-hover">
        <?php foreach ($items as $item) { ?>
            <tr>
                <td width="120"><?= $item->username ?></td>
                <td width="120"><?= $item->mobile ?></td>
                <td><?=\jeemoo\rbac\models\UserRole::getRoleNames($item)?></td>
                <td width="200"><?= $item->email ?></td>
                <td width="150"><?= date('Y-m-d H:i', $item->last_login_at) ?></td>
                <td width="150"><?= $item->last_login_ip ?></td>
                <td width="150"><?= date('Y-m-d', $item->create_at) ?></td>
                <th width="200">
                    <?php if (!$item->is_super) { ?>
                        <?= \jeemoo\rbac\common\HtmlUtils::a('角色', 'user-role/edit', ['id' => $item->id], ['class' => 'btn dialog', 'title' => '编辑角色'], ['height' => 350]) ?>
                        <a href="/user/update?id=<?=$item->id?>" class="btn dialog" title="编辑用户" data-height="300">编辑</a>
                        <a href="/user/password?id=<?=$item->id?>" class="btn dialog" title="修改密码" data-height="500">修改密码</a>
                        <a href="/user/delete" class="btn ajax-todo confirm" title="删除用户" data-id="<?=$item->id?>">删除</a>
                    <?php } ?>
                </th>
            </tr>
        <?php } ?>
    </table>
    <div class="fixed_full list-footer">
        <?= \yii\widgets\LinkPager::widget(['pagination' => $pager]); ?>
    </div>
</div>
<?php \yii\widgets\ActiveForm::end(); ?>

