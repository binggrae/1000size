<?php

use core\entities\size\Products;
use core\parsers\size\Parser;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel core\entities\ProductsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Товары';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="products-index">

    <div class="box box-primary">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'attribute' => 'barcode',
                        'format' => 'raw',
                        'value' => function (Products $model) {
                            return Html::a($model->title . ' (' . $model->barcode . ')', $model->link, [
                                'target' => '_blank'
                            ]);
                        }
                    ],
                    [
                        'attribute' => 'status',
                        'format' => 'raw',
                        'filter' => Products::getStatusList(),
                        'value' => function (Products $model) {
                            return Html::tag('span', $model->getStatusValue(), [
                                'class' => $model->getStatusClass()
                            ]);

                        }
                    ],
                    [
                        'header' => 'Цена',
                        'format' => 'raw',
                        'value' => function (Products $model) {
                            return Html::tag('div', 'Дилерская: ' . $model->purchase) .
                                Html::tag('div', 'Розница: ' . $model->retail);
                        }
                    ],
                    [
                        'header' => 'Остаток',
                        'format' => 'raw',
                        'value' => function (Products $model) {
                            return Html::tag('div', 'Москва: ' . $model->storageM) .
                                Html::tag('div', 'Владивосток: ' . $model->storageV);
                        }
                    ],
                    'load_ts:datetime',
                ],
            ]); ?>
        </div>
    </div>

</div>
