<?php


namespace core\parsers\size\pages;


interface CatalogPage
{

    public function getLink($barcode);

    public function getTitle();

    public function getUnit();

    public function getStorageM();

    public function getStorageV();

    public function getPurchase();

    public function getRetail();

}