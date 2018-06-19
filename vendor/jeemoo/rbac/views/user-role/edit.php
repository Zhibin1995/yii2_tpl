<?php

use common\models\User;
use jeemoo\rbac\models\Role;

/* @var $this yii\web\View */
/* @var $roles Role[] */
/* @var $userRoles Role[] */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="form-horizontal ajax-form record">
    <?php $form = yii\bootstrap\ActiveForm::begin(); ?>
    <div class="form-group">
        <label class="control-label col-xs-3">用户</label>

        <div class="col-xs-9 form-control-static">
            <?=$user->username?> (<?=$user->mobile?>)
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-3">角色</label>

        <div class="col-xs-9 checkbox">
            <?php foreach ($roles as $role) { ?>
                <?php
                $exist = false;
                foreach ($userRoles as $userRole) {
                    if ($userRole->id == $role->id) {
                        $exist = true;
                    }
                }
                ?>
                <label>
                    <input name="roles[]" value="<?= $role->id ?>" <?= $exist ? 'checked' : '' ?> type="checkbox"><?= $role->name ?>
                </label>
            <?php } ?>
        </div>
    </div>

    <div class="form-group fixed_full record-footer">
        <div class="col-xs-offset-3 col-xs-9">
            <input type="submit" class="btn btn-primary ajax-submit" value="保存"/>
        </div>
    </div>
    <?php yii\bootstrap\ActiveForm::end(); ?>
</div>
