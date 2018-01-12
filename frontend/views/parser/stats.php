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
                    <td>Date:</td>
                    <td><?=date('d.m.Y H:i:s', $date);?></td>
                </tr>
                <tr>
                    <td>Count:</td>
                    <td><?=$count;?></td>
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
