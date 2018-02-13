<?php


namespace core\entities;

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