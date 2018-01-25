<?php


namespace core\entities;

/**
 * Interface ProductInterface
 * @package core\entities
 *  * @property int $id
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
interface ProductInterface
{
    public function getBarcodeVal();

    public function getTitleVal();

    public function getUnitVal();

    public function getStorageMVal();

    public function getStorageVVal();

    public function getPurchaseVal();

    public function getRetailVal();

    public function getBrandVal();

    public function getCountryVal();


}