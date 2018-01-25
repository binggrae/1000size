<?php


namespace core\services\power;



use core\entities\Products;

class Importer
{
    private $file;

    public function __construct($path)
    {
        $this->file = fopen($path, 'r');
    }


    /**
     * @throws \Exception
     */
    public function import()
    {
        Products::deleteAll();
        try {
            foreach ($this->read() as $row) {
                if ($row) {
                    $product = Products::create($row);
                    $product->save();
                }
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return \Generator|void
     */
    private function read()
    {
        while (!feof($this->file)) {
            $row = array_map('trim', (array)fgetcsv($this->file, 4096));

//            $row = new CsvRow($headers[0], $row[0]);
            yield $row[0];
        }
        return;

    }

}