<?php

use jeemoo\rbac\models\Role;
use jeemoo\rbac\models\Permission;

/* @var $this yii\web\View */
/* @var $model Role */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="form-horizontal ajax-form record">
    <?php $form = yii\bootstrap\ActiveForm::begin(); ?>
    <?= $form->field($model, 'name')->textInput() ?>
    <?= $form->field($model, 'description')->textInput() ?>
    <?php foreach (Permission::getUserItems() as $item) { ?>
        <div class="form-group">
            <label class="control-label col-xs-3"><?= $item->title ?></label>

            <div class="col-xs-9 checkbox">
                <?php foreach (Permission::getUserItems($item->id) as $perm) { ?>
                    <?php
                    $exist = false;
                    foreach ($model->getPermissions() as $permission) {
                        if ($permission->id == $perm->id) {
                            $exist = true;
                        }
                    }
                    ?>
                    <label>
                        <input name="permissions[]" value="<?= $perm->id ?>" <?= $exist ? 'checked' : '' ?> type="checkbox"><?= $perm->title ?>
                    </label>
                <?php } ?>
            </div>
        </div>
    <?php } ?>

    <div class="form-group fixed_full record-footer">
        <div class="col-xs-offset-3 col-xs-8">
            <input id="add-food" type="submit" class="btn btn-primary ajax-submit" value="确认添加"/>
        </div>
    </div>
    <?php yii\bootstrap\ActiveForm::end(); ?>
</div>

