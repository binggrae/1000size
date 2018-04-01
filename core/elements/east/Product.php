<?php

namespace  core\elements\east;

use core\entities\ProductInterface;

class Product implements ProductInterface
{
    public $barcode;
    public $title;
    public $storageV = 0;
    public $purchase;
    public $retail;

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
        return 'шт.';
    }

    public function getStorageMVal()
    {
        return null;
    }

    public function getStorageVVal()
    {
        return $this->storageV;
    }

    public function getPurchaseVal()
    {
        return $this->purchase;
    }

    public function getRetailVal()
    {
        return $this->retail;
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