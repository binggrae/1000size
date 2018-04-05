<?php


namespace core\services\exports;


class Xml
{
    protected $date = [];

    private $xml;

    private $root;

    private $fields;

    public function __construct($fields)
    {
        $this->date['ДатаСоздания'] = date('n/d/Y H:i:s A', time());
        $this->xml = new \domDocument("1.0", "utf-8");

        $this->root = $this->generateRoot();
        $this->fields = $fields;
    }

    public function addProduct(ProductExported $product)
    {
        $root = $this->xml->createElement('Товар');

        foreach ($product->export($this->fields) as $element) {
            if(!is_array($element)) {
                $root->appendChild($this->createElement($element));
            } else {
                foreach ($element as  $item) {
                    $root->appendChild($this->createElement($item));
                }
            }
        }

        $this->root->appendChild($root);
    }

    private function createElement(XmlElement $element)
    {
        $el = $this->xml->createElement($element->name, $element->value);
        if(!empty($element->attributes)) {
            foreach ($element->attributes as $key => $value) {
                $el->setAttribute($key, $value);
            }
        }

        return $el;
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