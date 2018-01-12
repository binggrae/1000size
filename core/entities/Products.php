<?php

namespace core\entities;

use Yii;

/**
 * This is the model class for table "products".
 *
 * @property int $id
 * @property string $barcode
 * @property string $title
 * @property string $unit
 * @property int $storageM
 * @property int $storageV
 * @property int $purchase
 * @property int $retail
 * @property string $brand
 * @property string $country
 */
class Products extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['barcode', 'title'], 'required'],
            [['storageM', 'storageV', 'purchase', 'retail'], 'integer'],
            [['barcode', 'title', 'unit', 'brand', 'country'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'barcode' => 'Артикул',
            'title' => 'Название',
            'unit' => 'Единица измерения',
            'storageM' => 'Склад  1',
            'storageV' => 'Склад  2',
            'purchase' => 'Цена закуп',
            'retail' => 'Цена розница',
            'brand' => 'Производитель',
            'country' => 'Страна ',
        ];
    }
}
