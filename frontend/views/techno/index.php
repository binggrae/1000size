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
        <div class="col-md-6">
            <h4>Статистика</h4>
            <table class="table">
                <tr>
                    <td>Date:</td>
                    <td><?=date('d.m.Y H:i:s',\Yii::$app->settings->get('techno.date'));?></td>
                </tr>
                <tr>
                    <td>Xml:</td>
                    <td>
                        <a href="/<?= \Yii::$app->settings->get('techno.xml'); ?>"><?= \Yii::$app->settings->get('techno.xml'); ?></a>
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-md-6">
            <a class="btn btn-primary" href="<?= Url::to('/techno/start'); ?>">
                Запустить
            </a>
        </div>
    </div>
</div>
