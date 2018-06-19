<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use jeemoo\rbac\models\Permission;

/* @var $this yii\web\View */
/* @var $model Permission */
/* @var $items array */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="permission-form form-horizontal ajax-form record">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'parent_id')->dropDownList(['0' => '根节点'] + $items) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'route')->textInput(['maxlength' => true]) ?>

    <div class="form-group  fixed_full record-footer">
        <div class="col-xs-offset-3 col-md-8">
            <?= Html::submitButton('确认保存', ['class' => 'btn btn-primary ajax-submit']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
