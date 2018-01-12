<?php

/* @var $this yii\web\View */

use yii\helpers\Url;

$this->title = 'Parser stats';
?>
<div class="parser-stats">
    <div class="row">
        <div class="col-md-6">
            <table class="table">
                <tr>
                    <td>Status:</td>
                    <td>
                        <div class="label label-<?= ($status ? 'primary' : 'success');?>">
                            <?= ($status ? 'Job' : 'Wait');?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Task:</td>
                    <td><?=$task;?></td>
                </tr>

                <tr>
                    <td>Date:</td>
                    <td><?=date('d.m.Y H:i:s', $date);?></td>
                </tr>
                <tr>
                    <td>Count:</td>
                    <td><?=$count;?></td>
                </tr>
				<tr>
                    <td>Xml:</td>
                    <td><a href="/<?=\Yii::$app->settings->get('file.xml');?>"><?=\Yii::$app->settings->get('file.xml');?></a></td>
                </tr>
				<tr>
                    <td>Xls:</td>
                    <td><a href="/<?=\Yii::$app->settings->get('file.xls');?>"><?=\Yii::$app->settings->get('file.xls');?></a></td>
                </tr>
            </table>
        </div>
        <div class="col-md-6">
            <?php if(!$status) : ?>
                <a class="btn btn-primary" href="<?=Url::to('/parser/start');?>">
                    Start
                </a>
            <?php endif;?>
        </div>
    </div>

</div>
