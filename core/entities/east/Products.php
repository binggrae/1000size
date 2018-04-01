<?php

namespace core\entities\east;

use core\entities\ProductInterface;

/**
 * This is the model class for table "power_products".
 *
 * @property int $id
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
class Products extends \yii\db\ActiveRecord implements ProductInterface
{

    const STATUS_NEW = 0;
    const STATUS_LOADED = 5;
    const STATUS_REMOVED = 10;
    const STATUS_IN_JOB = 15;


    public static function create($barcode)
    {
        $product = new self();
        $product->barcode = $barcode;
        $product->status = self::STATUS_NEW;
        $product->title = 'import';
        $product->unit = 'шт.';

        return $product;
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'east_products';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['barcode', 'title', 'status'], 'required'],
            [['storageM', 'storageV', 'status'], 'integer'],
            [['purchase', 'retail'], 'number'],
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
            'barcodeVal' => 'Артикул',
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

    /**
     * @return string
     */
    public function getBarcodeVal()
    {
        return $this->barcode;
    }

    /**
     * @return string
     */
    public function getCountryVal()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getTitleVal()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getUnitVal()
    {
        return $this->unit;
    }

    /**
     * @return int
     */
    public function getStorageMVal()
    {
        return $this->storageM;
    }

    /**
     * @return int
     */
    public function getStorageVVal()
    {
        return null;
    }

    /**
     * @return int
     */
    public function getPurchaseVal()
    {
        return $this->purchase;
    }

    /**
     * @return int
     */
    public function getRetailVal()
    {
        return $this->purchase * 1.3;
    }

    /**
     * @return string
     */
    public function getBrandVal()
    {
        return null;
    }
}
