
<?php

use yii\widgets\ActiveForm;
use common\models\User;

/* @var $model User */
/* @var $this yii\web\View */
?>

<div class="form-horizontal ajax-form record">
    <?php $form = ActiveForm::begin(); ?>
    <div class="form-group">
        <label class="control-label col-xs-3">用户</label>

        <div class="col-xs-9 form-control-static">
            <?=$model->username?> (<?=$model->mobile?>)
        </div>
    </div>
    <?= $form->field($model, 'password')->passwordInput(['placeholder'=>'登录密码']) ?>
    <?= $form->field($model, 'confirm_password')->passwordInput(['placeholder'=>'确认密码']) ?>
    <div class="form-group fixed_full record-footer">
        <div class="col-xs-offset-3 col-md-8">
            <input id="add-food" type="submit" class="btn btn-primary ajax-submit" value="确认修改"/>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

