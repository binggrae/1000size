<?php


namespace core\entities\automaster;


use core\entities\ProductInterface;

class Product implements ProductInterface
{
    private $barcode;
    private $title;
    private $unit;
    private $storage;
    private $purchase;
    private $brand;

    public function __construct(
        $barcode,
        $title,
        $unit,
        $storage,
        $purchase,
        $brand
    )
    {
        $this->barcode = $barcode;
        $this->title = $title;
        $this->unit = $unit;
        $this->storage = $storage;
        $this->purchase = $purchase;
        $this->brand = $brand;
    }

    public function getBarcodeVal()
    {
        return $this->barcode;
    }

    public function getTitleVal()
    {
        return $this->title;
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
        return $this->purchase;
    }

    public function getRetailVal()
    {
        try {
            return round($this->purchase * 1.3, 2);
        } catch (\Exception $e) {
            var_dump($this->purchase);
            throw $e;
        }
    }

    public function getBrandVal()
    {
        return $this->brand;
    }

    public function getCountryVal()
    {
        return null;
    }


}