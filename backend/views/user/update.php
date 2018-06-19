
<?php

use yii\widgets\ActiveForm;
use common\models\User;

/* @var $model User */
/* @var $this yii\web\View */
?>

<div class="form-horizontal ajax-form record">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'username')->textInput(['placeholder'=>'姓名']) ?>
    <?= $form->field($model, 'mobile')->textInput(['placeholder'=>'登录帐号']) ?>
    <?= $form->field($model, 'email')->textInput(['placeholder'=>'邮箱']) ?>
    <div class="form-group fixed_full record-footer">
        <div class="col-xs-offset-3 col-md-8">
            <input id="add-food" type="submit" class="btn btn-primary ajax-submit" value="确认添加"/>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

