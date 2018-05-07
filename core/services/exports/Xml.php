<?php


namespace core\services\exports;


class Xml
{
    protected $attributes = [];

    private $xml;

    private $root;

    private $fields;

    public function __construct($fields)
    {
        $this->attributes['ДатаСоздания'] = date('n/d/Y H:i:s A', time());
        $this->xml = new \domDocument("1.0", "utf-8");

        $this->root = $this->generateRoot();
        $this->fields = $fields;
    }

    public function addAttribute($attr, $value)
    {
        $this->attributes[$attr] = $value;
    }

    public function addProduct(ProductExported $product)
    {
        if ($product->isLoad()) {
            $root = $this->xml->createElement('Товар');
            if (!empty($product->getXmlAttributes())) {
                foreach ($product->getXmlAttributes() as $key => $value) {
                    $root->setAttribute($key, $value);
                }
            }

            foreach ($product->export($this->fields) as $element) {
                if (!is_array($element)) {
                    $root->appendChild($this->createElement($element));
                } else {
                    foreach ($element as $item) {
                        $root->appendChild($this->createElement($item));
                    }
                }
            }

            $this->root->appendChild($root);
        }
    }

    private function createElement(XmlElement $element)
    {
        $element->value = is_double($element->value) ?
            number_format($element->value, 2, ',', '')
            : $element->value;
        $el = $this->xml->createElement($element->name, $element->value);
        if (!empty($element->attributes)) {
            foreach ($element->attributes as $key => $value) {
                $el->setAttribute($key, $value);
            }
        }

        return $el;
    }


    private function generateRoot()
    {
        $root = $this->xml->createElement('ЦеныИОстатки');
        foreach ($this->attributes as $key => $value) {
            $root->setAttribute($key, $value);
        }

        $this->xml->appendChild($root);

        return $root;
    }


    public function save($alias)
    {
        $this->xml->save(\Yii::getAlias($alias));
    }


}