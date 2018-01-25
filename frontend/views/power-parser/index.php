<?php

/* @var $this yii\web\View */

use yii\helpers\Url;

$this->title = 'Power Parser stats';
?>
<div class="parser-stats">
    <div class="row">
        <div class="col-md-6">
            <table class="table">
                <tr>
                    <td>Ожидают:</td>
                    <td><?= $imported; ?></td>
                </tr>
                <tr>
                    <td>Загружено:</td>
                    <td><?= $loaded; ?></td>
                </tr>
                <tr>
                    <td>Удалено:</td>
                    <td><?= $removed; ?></td>
                </tr>
            </table>
        </div>
        <div class="col-md-6">
            <a class="btn btn-primary" href="<?= Url::to('/power-parser/start'); ?>">
                Start
            </a>
        </div>
    </div>

</div>
