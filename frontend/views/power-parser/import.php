<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \core\forms\power\LoadForm */

$this->title = 'Загрузка артиклов';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="power-form box box-primary">
    <?php $form = ActiveForm::begin(['options' => [
        'enctype' => 'multipart/form-data'
    ]]); ?>
    <div class="box-body table-responsive">
        <?= $form->field($model, 'file')->fileInput(); ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
