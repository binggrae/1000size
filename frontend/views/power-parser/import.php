<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \core\forms\power\LoadForm*/

$this->title = 'Загрузка артиклов';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="certificate-load">
    <?php $form = ActiveForm::begin(['options' => [
        'enctype' => 'multipart/form-data'
    ]]); ?>

    <?= $form->field($model, 'file')->fileInput(); ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
