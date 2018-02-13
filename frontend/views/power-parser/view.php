<?php

/* @var $this yii\web\View */

/* @var $products Products[] */

use core\entities\power\Products;
use yii\helpers\Url;

$this->title = '1000size Parser stats';
?>
<div class="size-index">
    <table class="table">
        <thead></thead>
        <tr>
            <th>Barcode</th>
            <th>Title</th>
        </tr>
        <tbody>
        <?php foreach ($products as $product) : ?>
            <tr class="<?= $product->status === Products::STATUS_LOADED ? 'success' : 'danger' ?>">
                <td>
                    <?=$product->barcode;?>
                </td>
                <td>
                    <?=$product->title;?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>


