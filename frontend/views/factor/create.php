<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model core\entities\Factor */

$this->title = 'Создание наценки';
$this->params['breadcrumbs'][] = ['label' => 'Наценка', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="factor-create">

    <?php $form = ActiveForm::begin(); ?>
    <div class="box box-primary">
        <div class="box-body">
            <?= $form->field($model, 'key')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'value')->textInput() ?>
        </div>
        <div class="box-footer">
            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
