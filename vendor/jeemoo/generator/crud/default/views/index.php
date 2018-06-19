<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator jeemoo\generator\crud\Generator */
/* @var $db \yii\db\Connection */
/* @var $module string */
/* @var $model \yii\db\ActiveRecord */
/* @var $relations array list of relations (name => relation declaration) */

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;
use <?= $generator->ns . '\\' . $generator->getModelClass() ?>;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $items <?= ltrim($generator->modelClass, '\\') ?>[] */
/* @var $pager yii\data\Pagination */
/* @var $form yii\widgets\ActiveForm */
?>

<?= "<?php " ?>$form = ActiveForm::begin(); ?>
<div class="form-horizontal list">
    <div class="fixed_full list-header">
        <div class="form-group">
<?php foreach ($tableSchema->columns as $attribute) {
    if ($attribute->type=='string'&& !strpos($attribute->name,'img')&& !in_array($attribute->name, ['img_url','summary'])&&$filed=$generator->generateSearchField($tableSchema,$attribute->name)) {?>
            <div class="col-md-2">
                <?= "<?= " ?><?=$filed?>?>
            </div>
   <?php }
} ?>
            <div class="col-md-1">
                <?= "<?=" ?>Html::submitButton('查询', ['class' => 'btn btn-primary ajax-refresh']) ?>
            </div>
            <div class="col-md-1">
                <?= "<?=" ?>\jeemoo\rbac\common\HtmlUtils::a('添加','<?= lcfirst( Inflector::camel2id($generator->modelClass))?>/create',[],['class'=>'btn btn-white dialog','title'=>'添加'],['width'=>800, 'height'=>600]) ?>
            </div>
        </div>
        <table class="table">
            <tr>
<?php $index=0; foreach ($tableSchema->getColumnNames() as $key => $attribute) {
    if (!strpos($attribute,'img')&&!in_array($attribute, ['id','avatar', 'create_user', 'create_at', 'update_user', 'update_at','img_url','summary','details','description']) && $key < 20) {  $index++;
        echo "                <th" . ($index != 1 ? " width=\"100\"" : "") . "><?=\$model->getAttributeLabel('$attribute')?></th>"."\n";
    }
} ?>
                <th width="150">添加时间</th>
                <th width="100" class="text-right">操作</th>
            </tr>
        </table>
    </div>

    <table class="table table-hover">
       <?= "<?php " ?>foreach ($items as $item) { ?>
            <tr>
<?php $index = 0;
    foreach ($tableSchema->getColumnNames() as $key => $attribute) {
        if (!strpos($attribute, 'img') && !in_array($attribute, ['id','avatar', 'create_user', 'create_at', 'update_user', 'update_at', 'img_url', 'summary', 'details', 'description']) && $key < 10) {
            $index++;
            $column=$tableSchema->columns[$attribute];
            if (substr($column->dbType,0,4)=="enum"){
                echo "                <td" . ($index != 1 ? " width=\"100\"" : "") . "><?=\$item->get".Inflector::id2camel( $column->name)."Name() ?></td>" . "\n";
                continue;
            }
            $relation = null;
            $relationName='';
            $relationField='id';
            foreach ($relations as $name => $item) {
                if ($item[3] == $attribute) {
                    $relation = $item;
                    $relationName=$name;
                    break;
                }
            }

            if (!empty($relation) && $table = $db->getTableSchema(str_replace('-', '_', Inflector::camel2id($relation[1])))) {
                foreach($table->columns as $column){
                    if($column->name=='name'||$column->name=='title'){
                        $relationField=$column->name;
                        break;
                    }
                }
            }
echo "                <td" . ($index != 1 ? " width=\"100\"" : "") . "><?=\$item->".(empty($relation)?$attribute:(lcfirst($relationName)."->".$relationField))." ?></td>" . "\n";
        }
} ?>
                <td width="150"><?= "<?= " ?>date('Y-m-d H:i:s', $item->create_at) ?></td>
                <th width="100"  class="text-right">
                    <?= "<?= " ?> \jeemoo\rbac\common\HtmlUtils::a('编辑', '<?= lcfirst(str_replace('-', '_', Inflector::camel2id($generator->modelClass)))?>/update', ['id' => $item->id], ['class' => 'btn dialog', 'title' => '编辑'], ['height' => 600,'width'=>800]) ?>
                    <?= "<?= " ?>\jeemoo\rbac\common\HtmlUtils::a('删除','<?= lcfirst(str_replace('-', '_', Inflector::camel2id($generator->modelClass)))?>/delete',[],['class'=>'btn ajax-todo confirm','title'=>'删除'],['id'=> $item->id]) ?>
                </th>
            </tr>
        <?= "<?php " ?> } ?>
    </table>
    <div class="fixed_full list-footer">
        <?= "<?= " ?> LinkPager::widget(['pagination' => $pager]); ?>
    </div>
</div>
<?= "<?php " ?> ActiveForm::end(); ?>

