<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;
use jeemoo\rbac\models\Menu;
use jeemoo\rbac\common\HtmlUtils;
use jeemoo\rbac\common\BaseController;

/* @var $this yii\web\View */
/* @var $model Menu */
/* @var $parent Menu */
/* @var $form yii\widgets\ActiveForm */
/* @var $controller BaseController */

$controller=$this->context;
?>

<?php $form = ActiveForm::begin(); ?>
<div class="form-horizontal list">
    <div class="fixed_full list-header">
        <div class="form-group">
            <div class="col-md-2 form-control-static" style="padding-left:20px;">
                <?php if ($parent !== null) { ?>
                    <strong> 当前菜单：<?= $parent->title ?></strong>
                <?php } else { ?>
                    <strong> 当前菜单：根节点</strong>
                <?php } ?>
            </div>
            <div class="col-md-1">
                <?=HtmlUtils::a('添加',$controller->getModulePath().'menu/create',['id'=>$parent?$parent->id:0],['class'=>'btn btn-primary dialog','title'=>'添加菜单'],[]) ?>
            </div>
            <?php if ($parent !== null) { ?>
                <div class="col-md-1">
                    <a href="/<?=$controller->getModulePath()?>menu/index" class="btn btn-white" title="返回">返回</a>
                </div>
            <?php } ?>
        </div>
        <table class="table">
            <tr>
                <th width="35"></th>
                <th width="120"><?= $model->getAttributeLabel('title') ?></th>
                <th width="100">子菜单</th>
                <th><?= $model->getAttributeLabel('route') ?></th>
                <th width="200"><?= $model->getAttributeLabel('icon') ?></th>
                <th width="100"><?= $model->getAttributeLabel('sort') ?></th>
                <th width="100">添加时间</th>
                <th width="100" class="text-right">操作</th>
            </tr>
        </table>
    </div>

    <table class="table table-hover dragable">
        <?php foreach ($items as $item) { ?>
            <tr>
                <td data-id="<?php echo $item->id ?>" class="drag" width="35">
                    <i class="fa fa-arrows"></i>
                </td>
                <td width="120">
                    <?php if ($parent === null) { ?>
                        <a href="/<?=$controller->getModulePath()?>menu/index?id=<?= $item->id ?>"><?= $item->title ?></a>
                    <?php } else { ?>
                        <?= $item->title ?>
                    <?php } ?>
                </td>
                <td width="100"><?= $item->child_count ?></td>
                <td><?= $item->route ?></td>
                <td width="200"><?= $item->icon ?></td>
                <td width="100"><?= $item->sort ?></td>
                <td width="100"><?= date('Y-m-d', $item->create_at) ?></td>
                <th width="100" class="text-right">
                    <?=HtmlUtils::a('编辑',$controller->getModulePath().'menu/update',['id'=> $item->id],['class'=>'btn dialog','title'=>'编辑菜单'],[]) ?>
                    <?=HtmlUtils::a('删除',$controller->getModulePath().'menu/delete',[],['class'=>'btn ajax-todo confirm','title'=>'删除菜单'],['id'=> $item->id]) ?>
                </th>
            </tr>
        <?php } ?>
    </table>
<?php ActiveForm::end(); ?>
<script type="text/javascript">
    $(".dragable").dragsort({
        dragSelector: "td.drag", dragSelectorExclude: ".title", dragBetween: false, dragEnd: function saveOrder() {
            var data = $(".dragable tr").map(function () {
                return $(this).find(".drag").data("id");
            }).get();
            $.post("/<?=$controller->getModulePath()?>menu/order?id=<?php echo Yii::$app->request->get('id',0) ?>", {order: data});
        }
    });
</script>