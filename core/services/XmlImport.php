<?php


namespace core\services;


class XmlImport
{

    private $xml;

    public function __construct($link)
    {
        $this->xml = $this->xml2array(simplexml_load_file($link));
    }

    public function getList()
    {
        return $this->xml['shop']['offers']['vendorCode'];
    }


    private function xml2array($xmlObject, $out = array())
    {
        foreach ((array)$xmlObject as $index => $node) {
            $out[$index] = (is_object($node)) ? $this->xml2array($node) : $node;
        }

        return $out;
    }


}