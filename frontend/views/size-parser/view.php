<?php

/* @var $this yii\web\View */

/* @var $products Products[] */

use core\entities\size\Categories;
use core\entities\size\Products;
use yii\helpers\Url;

$this->title = '1000size Parser stats';
?>
<div class="size-index">
    <table class="table">
        <thead></thead>
        <tr>
            <th>Title</th>
        </tr>
        <tbody>
        <?php foreach ($products as $product) : ?>
            <tr class="<?= $product->status === 0 ? 'success' : 'danger' ?>">
                <td>
                    <a href="https://opt.1000size.ru<?=$product->link;?>" target="_blank">
                        <?=$product->title;?>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>


