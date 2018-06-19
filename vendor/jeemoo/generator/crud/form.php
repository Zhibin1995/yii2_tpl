<style>
    .form-group{height:30px;}
</style>
<?php
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator jeemoo\generator\crud\Generator */

echo '<h3>General Configuration</h2>';

echo $form->field($generator, 'tableName')->textInput(['table_prefix' => $generator->getTablePrefix()]);
echo $form->field($generator, 'ns')->textInput(['table_prefix' => $generator->getTablePrefix()]);
echo $form->field($generator, 'baseClass')->textInput(['table_prefix' => $generator->getTablePrefix()]);
echo $form->field($generator, 'controllerNs')->textInput(['table_prefix' => $generator->getTablePrefix()]);
echo $form->field($generator, 'controllerBaseClass')->textInput(['table_prefix' => $generator->getTablePrefix()]);