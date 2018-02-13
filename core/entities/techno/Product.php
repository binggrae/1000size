<?php


namespace core\entities\techno;


use core\entities\ProductInterface;

class Product implements ProductInterface
{
    private $barcode;
    private $unit;
    private $storage;
    private $purchase;
    private $retail;

    public function __construct(
        $barcode,
        $unit,
        $storage,
        $purchase,
        $retail
    )
    {
        $this->barcode = $barcode;
        $this->unit = $unit;
        $this->storage = $storage;
        $this->purchase = $purchase;
        $this->retail = $retail;
    }

    public function getBarcodeVal()
    {
        return $this->barcode;
    }

    public function getTitleVal()
    {
        return '';
    }

    public function getUnitVal()
    {
        return $this->unit;
    }

    public function getStorageMVal()
    {
        return $this->storage;
    }

    public function getStorageVVal()
    {
        return null;
    }

    public function getPurchaseVal()
    {
        return $this->retail;
    }

    public function getRetailVal()
    {
        return $this->purchase;
    }

    public function getBrandVal()
    {
        return null;
    }

    public function getCountryVal()
    {
        return null;
    }


}