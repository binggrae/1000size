<?php

/* @var $this yii\web\View */

/* @var Log[] $model */
/* @var ParserLog $parent */

use core\entities\logs\Log;
use core\entities\logs\ParserLog;
use core\helpers\LogHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\helpers\VarDumper;

$this->title = 'Log view: ' .date('d.m.Y', $parent->date_start);
?>
<div class="log-index">
    <h1><?=$this->title;?></h1>

    <table class="table">
        <thead></thead>
        <tr>
            <th>type</th>
            <th>Time</th>
            <th>Status</th>
            <th>Link</th>
            <th>Count</th>
            <th>Error</th>
        </tr>
        <tbody>
        <?php foreach ($model as $item) : ?>
            <tr class="<?= LogHelper::getClassByCode($item->status); ?>">
                <td><?= $item->type; ?></td>
                <td><?= date('H:i:s', $item->date_start); ?> - <?= date('H:i:s', $item->date_end); ?></td>
                <td><?= LogHelper::getLabelByCode($item->status); ?></td>
                <td><a href="<?= $item->link; ?>" target="_blank"><?= $item->link; ?></a></td>
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
