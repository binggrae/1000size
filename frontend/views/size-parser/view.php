<?php

/* @var $this yii\web\View */

/* @var $products Products[] */
/* @var $category Categories */

use core\entities\size\Categories;
use core\entities\size\Products;
use yii\helpers\Url;

$this->title = '1000size. Категория: ' . $category->title;

$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['categories']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="group-index box box-primary">
    <div class="table-responsive no-padding">

        <div class="box-body">
            <div class="size-index">
                <table class="table">
                    <thead></thead>
                    <tr>
                        <th>Название</th>
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

        </div>
    </div>
</div>


