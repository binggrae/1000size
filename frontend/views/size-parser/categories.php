<?php

/* @var $this yii\web\View */

/* @var $categories Categories[] */

use core\entities\size\Categories;
use yii\helpers\Url;

$this->title = '1000size категории';
?>
<div class="group-index box box-primary">
    <div class="table-responsive no-padding">

        <div class="box-body">
            <table class="table">
                <thead></thead>
                <tr>
                    <th>Название</th>
                    <th>Ссылка</th>
                    <th>Загружено</th>
                    <th>Всего</th>
                    <th>Пропуск</th>
                </tr>
                <tbody>
                <?php foreach ($categories as $category) : ?>
                    <tr class="<?= $category->status === 0 ? 'success' : 'danger' ?>">
                        <td>
                            <a href="<?= Url::to(['/size-parser/view', 'id' => $category->id]); ?>">
                                <?= $category->title; ?>
                            </a>
                        </td>
                        <td>
                            <a href="https://opt.1000size.ru<?= $category->link; ?>" target="_blank">opt.1000size.ru</a>
                        </td>
                        <td>
                            <?= $category->getLoadedProductsCount() ?>
                        </td>
                        <td>
                            <?= $category->getAllProductsCount() ?>
                        </td>
                        <td>
                            <?php if ($category->getAllProductsCount() != $category->getLoadedProductsCount()) : ?>
                                <label for="" class="label label-danger">
                                    <?= $category->getAllProductsCount() - $category->getLoadedProductsCount(); ?>
                                </label>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


