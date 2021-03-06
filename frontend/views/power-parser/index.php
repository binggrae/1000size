<?php

/* @var $this yii\web\View */

use core\entities\power\Products;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;

$this->title = 'Power Parser stats';
?>
<div class="parser-stats">
    <div class="row">
        <div class="col-md-4">
            <h4>Статистика</h4>
            <table class="table">
                <tr>
                    <td>Ожидают:</td>
                    <td>
                        <a href="<?= Url::to(['/power-parser/list', 'status' => Products::STATUS_NEW]); ?>">
                            <?= $imported; ?>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>В работе:</td>
                    <td>
                        <a href="<?= Url::to(['/power-parser/list', 'status' => Products::STATUS_IN_JOB]); ?>">
                            <?= $job; ?>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>Загружено:</td>
                    <td>
                        <a href="<?= Url::to(['/power-parser/list', 'status' => Products::STATUS_LOADED]); ?>">
                            <?= $loaded; ?>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>Удалено:</td>
                    <td>
                        <a href="<?= Url::to(['/power-parser/list', 'status' => Products::STATUS_REMOVED]); ?>">
                            <?= $removed; ?>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>Xml:</td>
                    <td>
                        <a href="/<?= \Yii::$app->settings->get('power.xml'); ?>"><?= \Yii::$app->settings->get('power.xml'); ?></a>
                    </td>
                </tr>
                <tr>
                    <td>Xls:</td>
                    <td>
                        <a href="/<?= \Yii::$app->settings->get('power.xls'); ?>"><?= \Yii::$app->settings->get('power.xls'); ?></a>
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-md-4">
            <?php if ($job) : ?>
                <div class="btn btn-success disabled">
                    В работе
                </div>
            <?php else : ?>
                <a class="btn btn-primary" href="<?= Url::to('/power-parser/start'); ?>">
                    Запустить
                </a>
            <?php endif; ?>
        </div>
        <div class="col-md-4">
            <?php $form = ActiveForm::begin([
                'action' => Url::to(['/power-parser/load']),
                'options' => [
                    'enctype' => 'multipart/form-data'
                ]
            ]); ?>

            <?= $form->field($model, 'file')->fileInput(); ?>

            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
