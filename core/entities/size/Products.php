<?php

namespace core\entities\size;

use core\entities\ProductInterface;

/**
 * This is the model class for table "products".
 *
 * @property int $id
 * @property string $link
 * @property int $load_ts
 * @property int $status
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

    const STATUS_NEW = 0;
    const STATUS_ACTIVE = 5;
    const STATUS_INACTIVE = 10;
    const STATUS_REMOVE = 15;

    public static function create($barcode)
    {
        $product = new self();
        $product->barcode = $barcode;
        $product->title = 'new';
        $product->status = self::STATUS_NEW;

        return $product;
    }


    public static function tableName()
    {
        return 'products';
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_REMOVE => 'Удален',
            self::STATUS_NEW => 'Новый',
            self::STATUS_ACTIVE => 'Загружен',
            self::STATUS_INACTIVE => 'Не активен',
        ];
    }

    public function getStatusValue()
    {
        $labels = self::getStatusList();

        return $labels[$this->status];
    }


    public function getStatusClass()
    {
        $classes = [
            self::STATUS_REMOVE => 'label label-danger',
            self::STATUS_NEW => 'label label-info',
            self::STATUS_ACTIVE => 'label label-success',
            self::STATUS_INACTIVE => 'label label-warning',
        ];

        return $classes[$this->status];
    }

    public function rules()
    {
        return [
            [['barcode', 'title'], 'required'],
            [['storageM', 'storageV', 'purchase', 'retail', 'load_ts', 'status'], 'integer'],
            [['link', 'barcode', 'title', 'unit', 'brand', 'country'], 'string', 'max' => 255],
        ];
    }


    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'barcode' => 'Артикул',
            'status' => 'Статус',
            'load_ts' => 'Дата изменения',
            'titleVal' => 'Название',
            'unitVal' => 'Единица измерения',
            'storageMVal' => 'Склад  1',
            'storageVVal' => 'Склад  2',
            'purchaseVal' => 'Цена закуп',
            'retailVal' => 'Цена розница',
            'brandVal' => 'Производитель',
            'countryVal' => 'Страна ',
        ];
    }


}
