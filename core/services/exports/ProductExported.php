<?php


namespace core\services\exports;


abstract class ProductExported
{

    public function export($fields) {
        $result = [];
        foreach ($fields as $field) {
            $result[$field] = property_exists($this, $field) ? $this->$field : null;
        }
        return $result;
    }


}