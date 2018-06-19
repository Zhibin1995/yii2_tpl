<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator jeemoo\generator\crud\Generator */
/* @var $module string */
/* @var $model \yii\db\ActiveRecord */
/* @var $relations array list of relations (name => relation declaration) */

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use <?= $generator->ns . '\\' . $generator->getModelClass() ?>;
<?php foreach($relations as $name=>$relation){
    if(!$relation[2]){
        echo "use " . $generator->ns . "\\" . $relation[1] . ";\n";
    }
}?>

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form form-horizontal ajax-form record">

    <?= "<?php " ?>$form = ActiveForm::begin(); ?>

<?php foreach ($tableSchema-> getColumnNames() as $attribute) {
    if (!in_array($attribute, ['id','create_user','create_at','update_user','update_at'])) {
        $column = $tableSchema->columns[$attribute];
        if (!empty($column->comment) && ($pos = strpos($column->comment, '=')) > 0 && substr($column->comment, $pos + 1) == 'img') {
            echo '    <div class="form-group">
        <label class="control-label col-xs-3">' . substr($column->comment, 0, $pos) . '</label>

        <div class="col-xs-2">
            <div class="image list-image" data-width="300" data-height="300" data-name="'.$column->name.'"  style="width:82px;">
                <div class="select">
                    <div class="tip"> 选择图片</div>
                    <input type="file" />
                </div>
                <div class="container">
                    <?php if ($model->'.$column->name.') { ?>
                        <img src="<?= $model->get'.Inflector::id2camel(str_replace('_', '-', $column->name)).'() ?>"/>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div  class="col-xs-6" style="padding-top:63px;">300*300</div>
    </div>';
        } else {
            echo "    <?= " . $generator->generateActiveField($tableSchema, $attribute, $relations) . " ?>\n\n";
        }
    }
} ?>
    <div class="form-group  fixed_full record-footer">
        <div class="col-xs-offset-3 col-md-8">
        <?= "<?= " ?>Html::submitButton('确认保存', ['class' => $model->isNewRecord ? 'btn btn-success ajax-submit' : 'btn btn-primary ajax-submit']) ?>
        </div>
    </div>

    <?= "<?php " ?>ActiveForm::end(); ?>

</div>
<script type="text/javascript">
<?php foreach ($tableSchema-> columns as $column) {
    if($column->type=='text'){
echo "   UE.getEditor('".strtolower($tableSchema->name).'-'.$column->name."');";
    }
}?>

</script>