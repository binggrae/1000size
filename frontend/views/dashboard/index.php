<?php

/* @var $this yii\web\View */
/* @var $size [] */
/* @var $power [] */
/* @var $automaster [] */
/* @var $bch [] */
/* @var $east [] */

/* @var $techno [] */

use core\entities\power\Products as PowerProducts;
use yii\bootstrap\Html;
use yii\helpers\Url;

$this->title = 'Панель управления';
?>

<div class="dashboard-index">
    <div class="row">
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        1000size
                    </h3>
                    <div class="box-tools pull-right">
                        <?= Html::a('<i class="fa fa-list text-blue"></i>', ['/size/index'], [
                            'class' => 'btn btn-default btn-sm',
                            'title' => 'Статистика'
                        ]) ?>
                        <?= Html::a('<i class="fa fa-play text-green"></i>', ['/size/save'], [
                            'class' => 'btn btn-default btn-sm',
                            'title' => 'Запустить'
                        ]) ?>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive no-padding">
                        <table class="table">
                            <tr>
                                <td>Дата:</td>
                                <td><?= $size['date']; ?></td>
                            </tr>
                            <tr>
                                <td>Количество:</td>
                                <td><?= $size['count']; ?></td>
                            </tr>
                            <tr>
                                <td>Ошибки:</td>
                                <td>
                                    <a href="<?= Url::to(['size/error']) ?>" target="_blank"><?= $size['error']; ?></a>
                                </td>
                            </tr>
                            <tr>
                                <td>Xml:</td>
                                <td><a href="/<?= $size['xml']; ?>" target="_blank"><?= $size['xml']; ?></a></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        PowerGroup
                    </h3>
                    <div class="box-tools pull-right">
                        <div class="btn-group" data-toggle="btn-toggle">
                            <?php if ($power['status']) : ?>
                                <?= Html::tag('span', '<i class="fa fa-stop"></i>', [
                                    'class' => 'btn btn-danger btn-sm disabled',
                                    'title' => 'В работе'
                                ]) ?>
                            <?php else : ?>
                                <?= Html::a('<i class="fa fa-play text-green"></i>', ['/power-parser/start'], ['class' => 'btn btn-default btn-sm']) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive no-padding">
                        <table class="table">
                            <tr>
                                <td>Дата:</td>
                                <td><?= $power['date']; ?></td>
                            </tr>
                            <tr>
                                <td>Xml:</td>
                                <td>
                                    <a href="/<?= $power['xml']; ?>" target="_blank"><?= $power['xml']; ?></a>
                                </td>
                            </tr>
                            <tr>
                                <td>Ошибки:</td>
                                <td>
                                    <a href="/<?= $power['error']; ?>" target="_blank"><?= $power['count']; ?></a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        Automaster
                    </h3>
                    <div class="box-tools pull-right">
                        <div class="btn-group" data-toggle="btn-toggle">
                            <?= Html::a('<i class="fa fa-play text-green"></i>', ['/automaster/start'], ['class' => 'btn btn-default btn-sm']) ?>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive no-padding">
                        <table class="table">
                            <tr>
                                <td>Дата:</td>
                                <td><?= $automaster['date']; ?></td>
                            </tr>
                            <tr>
                                <td>Xml:</td>
                                <td>
                                    <a href="/<?= $automaster['xml']; ?>" target="_blank"><?= $automaster['xml']; ?></a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        Technomarin
                    </h3>
                    <div class="box-tools pull-right">
                        <div class="btn-group" data-toggle="btn-toggle">
                            <?= Html::a('<i class="fa fa-play text-green"></i>', ['/techno/start'], ['class' => 'btn btn-default btn-sm']) ?>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive no-padding">
                        <table class="table">
                            <tr>
                                <td>Дата:</td>
                                <td><?= $techno['date']; ?></td>
                            </tr>
                            <tr>
                                <td>Xml:</td>
                                <td>
                                    <a href="/<?= $techno['xml']; ?>" target="_blank"><?= $techno['xml']; ?></a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        Eastmarine
                    </h3>
                    <div class="box-tools pull-right">
                        <div class="btn-group" data-toggle="btn-toggle">
                            <?php if ($east['status']) : ?>
                                <?= Html::tag('span', '<i class="fa fa-stop"></i>', [
                                    'class' => 'btn btn-danger btn-sm disabled',
                                    'title' => 'В работе'
                                ]) ?>
                            <?php else : ?>
                                <?= Html::a('<i class="fa fa-play text-green"></i>', ['/east/start'], ['class' => 'btn btn-default btn-sm']) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive no-padding">
                        <table class="table">
                            <tr>
                                <td>Дата:</td>
                                <td><?= $east['date']; ?></td>
                            </tr>
                            <tr>
                                <td>Xml:</td>
                                <td>
                                    <a href="/<?= $east['xml']; ?>" target="_blank"><?= $east['xml']; ?></a>
                                </td>
                            </tr>
                            <tr>
                                <td>Ошибки:</td>
                                <td>
                                    <a href="/<?= $east['error']; ?>" target="_blank"><?= $east['count']; ?></a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        Bch5
                    </h3>
                    <div class="box-tools pull-right">
                        <div class="btn-group" data-toggle="btn-toggle">
                            <?php if ($bch['status']) : ?>
                                <?= Html::tag('span', '<i class="fa fa-stop"></i>', [
                                    'class' => 'btn btn-danger btn-sm disabled',
                                    'title' => 'В работе'
                                ]) ?>
                            <?php else : ?>
                                <?= Html::a('<i class="fa fa-play text-green"></i>', ['/bch/start'], ['class' => 'btn btn-default btn-sm']) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive no-padding">
                        <table class="table">
                            <tr>
                                <td>Дата:</td>
                                <td><?= $bch['date']; ?></td>
                            </tr>
                            <tr>
                                <td>Xml:</td>
                                <td>
                                    <a href="/<?= $bch['xml']; ?>" target="_blank"><?= $bch['xml']; ?></a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

    </div>
</div>
