<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;
use jeemoo\rbac\models\Role;
use jeemoo\rbac\common\HtmlUtils;
use jeemoo\rbac\common\BaseController;

/* @var $this yii\web\View */
/* @var $model Role */
/* @var $items Role[] */
/* @var $pager LinkPager */
/* @var $form yii\widgets\ActiveForm */
/* @var $controller BaseController */

$controller=$this->context;
?>
<?php $form = \yii\widgets\ActiveForm::begin(); ?>
<div class="form-horizontal list">
    <div class="fixed_full list-header">
        <div class="form-group">
            <div class="col-md-3">
                <?= \yii\bootstrap\Html::activeTextInput($model, 'name', ['class' => 'form-control','placeholder'=>'角色名称']) ?>
            </div>
            <div class="col-md-1">
                <?= \yii\helpers\Html::submitButton('查询', ['class' => 'btn btn-primary ajax-refresh']) ?>
            </div>
            <div class="col-md-1">
                <?=HtmlUtils::a('添加',$controller->getModulePath().'role/create',[],['class'=>'btn btn-white dialog','title'=>'添加角色'],['width'=>800, 'height'=>600]) ?>
            </div>
        </div>

        <table class="table">
            <tr>
                <th width="200">角色名称</th>
                <th>角色说明</th>
                <th width="120">添加时间</th>
                <th width="100">操作</th>
            </tr>
        </table>
    </div>
    <table class="table table-hover list-content">
        <?php foreach ($items as $item) { ?>
            <tr>
                <td width="200"><?= $item->name ?></td>
                <td><?= $item->description ?></td>
                <td width="120"><?= date('Y-m-d', $item->create_at) ?></td>
                <th width="100">
                    <?=HtmlUtils::a('编辑',$controller->getModulePath().'role/update',['id'=> $item->id],['class'=>'btn dialog','title'=>'编辑角色'],['width'=>800, 'height'=>500]) ?>
                    <?=HtmlUtils::a('删除',$controller->getModulePath().'role/delete',[],['class'=>'btn ajax-todo confirm','title'=>'删除角色'],['id'=> $item->id]) ?>
                </th>
            </tr>
        <?php } ?>
    </table>
    <div class="fixed_full list-footer">
        <?= \yii\widgets\LinkPager::widget(['pagination' => $pager]); ?>
    </div>
</div>
<?php \yii\widgets\ActiveForm::end(); ?>
