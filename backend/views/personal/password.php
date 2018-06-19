<div class="form-horizontal ajax-form">
    <?php $form = yii\bootstrap\ActiveForm::begin(['id' => 'password-form']); ?>
    <?= $form->field($model, 'orig_password')->passwordInput() ?>

    <?= $form->field($model, 'new_password')->passwordInput() ?>

    <?= $form->field($model, 'confirm_password')->passwordInput() ?>

    <div class="form-group">
        <div class="col-xs-offset-3 col-md-8">
            <?= yii\helpers\Html::submitButton('确认修改', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
        </div>
    </div>
    <?php yii\bootstrap\ActiveForm::end(); ?>
</div>
