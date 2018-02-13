<?php


namespace console\controllers;


use core\entities\techno\Product;
use core\services\techno\Xml;
use yii\console\Controller;

class TechnoController extends Controller
{

    public function actionRun()
    {
        $dir = \Yii::getAlias('@common/data/');
        $local = $dir . '/techno.zip';
        $xml = $dir . '/price.xml';

        $connection = ftp_connect('mx1.technomarin.ru');
        $auth = ftp_login($connection, 'info@west-marine.ru', '00270061');
        ftp_pasv($connection, true);

        if ($auth && ftp_get($connection, $local, '/price.zip', FTP_BINARY)) {
            $zip = new \ZipArchive;
            $zip->open($local);
            $zip->extractTo($dir);
            $zip->close();

            $xml = simplexml_load_file($xml);
            $products = [];

            foreach ($xml->{'Товар'} as $item) {
                $product = new Product(
                    (string)$item->{'Артикул'},
                    (string)$item->{'ЕдИзм'},
                    (string)$item->{'Остаток'},
                    str_replace(' ', '', (string)$item->{'ЦенаРозничная'}),
                    str_replace(' ', '', (string)$item->{'ЦенаДилерская'})
                );
                $products[] = $product;
            }

            $xmlService = new Xml((string)$xml['ДатаСоздания'], $products);
            $xmlService->generate();
            $xmlService->save($dir . '/result.xml');
        }

    }
}