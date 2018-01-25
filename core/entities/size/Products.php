<?php

namespace core\entities\size;

use core\entities\ProductInterface;
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
class Products extends \yii\db\ActiveRecord implements ProductInterface
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
        return $this->country;
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
        return $this->storageV;
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
        return $this->retail;
    }

    /**
     * @return string
     */
    public function getBrandVal()
    {
        return $this->brand;
    }
}
