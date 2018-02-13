<?php


namespace core\services;


use core\entities\ProductInterface;

class Xml
{

    protected $date = [];

    private $products;

    private $xml;

    /**
     * Xml constructor.
     * @param ProductInterface[] $products
     */
    public function __construct($products)
    {
        $this->date['ДатаСоздания'] = date('n/d/Y H:i:s A', time());
        $this->products = $products;
        $this->xml = new \domDocument("1.0", "utf-8");
    }


    public function generate()
    {
        $root = $this->generateRoot();

        foreach ($this->products as $product) {
            $this->generateProduct($root, $product);
        }
    }

    /**
     * @param \DOMElement $root
     * @param ProductInterface $product
     */
    private function generateProduct($root, ProductInterface $product)
    {
        $el = $this->xml->createElement('Товар');

        if (!is_null($product->getBarcodeVal())) {
            $el->appendChild($this->generateBarcode($product->getBarcodeVal()));
        }
        if (!is_null($product->getTitleVal())) {
            $el->appendChild($this->generateTitle($product->getTitleVal()));
        }

        if (!is_null($product->getUnitVal())) {
            $el->appendChild($this->generateUnit($product->getUnitVal()));
        }
        if (!is_null($product->getStorageMVal())) {
            $el->appendChild($this->generateStorageM($product->getStorageMVal()));
        }
        if (!is_null($product->getStorageVVal())) {
            $el->appendChild($this->generateStorageV($product->getStorageVVal()));
        }

        if (!is_null($product->getRetailVal())) {
            $el->appendChild($this->generateRetail($product->getRetailVal()));
        }
        if (!is_null($product->getPurchaseVal())) {
            $el->appendChild($this->generatePurchase($product->getPurchaseVal()));
        }
        if (!is_null($product->getBrandVal())) {
            $el->appendChild($this->generateBrand($product->getBrandVal()));
        }
        if (!is_null($product->getCountryVal())) {
            $el->appendChild($this->generateCountry($product->getCountryVal()));
        }

        $root->appendChild($el);

    }

    private function generateBarcode($barcode)
    {
        return $this->xml->createElement('Артикул', $barcode);
    }

    private function generateTitle($title)
    {
        $title = str_replace('&', '_', $title);
        return $this->xml->createElement('Наименование', $title);

    }

    private function generateUnit($unit)
    {
        return $this->xml->createElement('ЕдИзм', $unit);
    }

    private function generateStorageM($storageM)
    {
        $storage = $this->xml->createElement('Остаток', $storageM);
        $storage->setAttribute('Склад', 'Склад Москва');
        return $storage;
    }

    private function generateStorageV($storageV)
    {
        $storage = $this->xml->createElement('Остаток', $storageV);
        $storage->setAttribute('Склад', 'Склад Владивосток');
        return $storage;
    }

    private function generateRetail($retail)
    {
        return $this->xml->createElement('ЦенаРозничная', $retail);
    }

    private function generatePurchase($purchase)
    {
        return $this->xml->createElement('ЦенаДилерская', $purchase);
    }

    private function generateBrand($brand)
    {
        $brand = str_replace('&', ' ', $brand);

        return $this->xml->createElement('Производитель', $brand);
    }

    private function generateCountry($country)
    {
        return $this->xml->createElement('Страна', $country);
    }

    private function generateRoot()
    {
        $root = $this->xml->createElement('ЦеныИОстатки');
        foreach ($this->date as $key => $value) {
            $root->setAttribute($key, $value);
        }

        $this->xml->appendChild($root);

        return $root;
    }


    public function save($path)
    {
        $this->xml->save(\Yii::getAlias($path));
    }

}