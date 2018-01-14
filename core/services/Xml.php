<?php


namespace core\services;


use core\entities\Products;

class Xml
{
    
    private $products;
    
    private $xml;

    public function __construct($products)
    {
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
     * @param Products $product
     */
    private function generateProduct($root, $product)
    {
        $el = $this->xml->createElement('Товар');

        $el->appendChild($this->generateBarcode($product->barcode));
        $el->appendChild($this->generateTitle($product->title));

        $el->appendChild($this->generateUnit($product->unit));
        $el->appendChild($this->generateStorageM($product->storageM));
        $el->appendChild($this->generateStorageV($product->storageV));

        $el->appendChild($this->generateRetail($product->retail));
        $el->appendChild($this->generatePurchase($product->purchase));
        $el->appendChild($this->generateBrand($product->brand));
        $el->appendChild($this->generateCountry($product->country));

        $root->appendChild($el);

    }
    private function generateBarcode($barcode)
    {
        return $this->xml->createElement('Артикул', $barcode);
    }

    private function generateTitle($title)
    {
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
		$brand = str_replace('&', ' ',  $brand);
	
		return $this->xml->createElement('Производитель', $brand);
	
    }

    private function generateCountry($country)
    {
        return $this->xml->createElement('Страна', $country);
    }

    private function generateRoot()
    {
        $root = $this->xml->createElement('ЦеныИОстатки');
        $root->setAttribute('ДатаСоздания', date('n/d/Y H:i:s A', time()));

        $this->xml->appendChild($root);
        
        return $root;
    }
    
    
    public function save($path)
    {
        $this->xml->save(\Yii::getAlias($path));
    }

}