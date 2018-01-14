<?php

/* @var $this yii\web\View */

/* @var ParserLog[] $model */

use core\entities\logs\ParserLog;
use core\helpers\LogHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\helpers\VarDumper;

$this->title = 'Log list';
?>
<div class="log-index">

    <table class="table">
        <thead></thead>
        <tr>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
            <th>Count</th>
            <th>Error</th>
        </tr>
        <tbody>
        <?php foreach ($model as $item) : ?>
            <tr class="<?= LogHelper::getClassByCode($item->status); ?>">
                <td>
                    <a href="<?=Url::to(['/log/view', 'id' => $item->id]);?>">
                        <?= date('d.m.Y', $item->date_start); ?>
                    </a>
                </td>
                <td><?= date('H:i:s', $item->date_start); ?> - <?= date('H:i:s', $item->date_end); ?></td>
                <td><?= LogHelper::getLabelByCode($item->status); ?></td>
                <td><?= $item->count; ?></td>
                <td>
                    <?php if ($item->error_data) : ?>
                        <pre><?= VarDumper::dumpAsString(Json::decode($item->error_data)); ?></pre>
                    <?php else: ?>
                    not error

                    <?php endif;?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</div>
