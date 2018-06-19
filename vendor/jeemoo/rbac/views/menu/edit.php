<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use jeemoo\rbac\models\Menu;

/* @var $this yii\web\View */
/* @var $model Menu */
/* @var $menus array */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-form form-horizontal ajax-form record">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'parent_id')->dropDownList(['0' => '根节点'] + $menus) ?>

    <?= $form->field($model, 'icon')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'route')->textInput(['maxlength' => true]) ?>

    <div class="form-group  fixed_full record-footer">
        <div class="col-xs-offset-3 col-md-8">
            <?= Html::submitButton('确认保存', ['class' => $model->isNewRecord ? 'btn btn-success ajax-submit' : 'btn btn-primary ajax-submit']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
