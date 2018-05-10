<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model core\entities\ProductsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="products-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'link') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'barcode') ?>

    <?= $form->field($model, 'title') ?>

    <?php // echo $form->field($model, 'unit') ?>

    <?php // echo $form->field($model, 'storageM') ?>

    <?php // echo $form->field($model, 'storageV') ?>

    <?php // echo $form->field($model, 'purchase') ?>

    <?php // echo $form->field($model, 'retail') ?>

    <?php // echo $form->field($model, 'brand') ?>

    <?php // echo $form->field($model, 'country') ?>

    <?php // echo $form->field($model, 'load_ts') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
