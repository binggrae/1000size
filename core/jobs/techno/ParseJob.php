<?php


namespace core\jobs\techno;


use core\entities\Factor;
use core\entities\size\Categories;
use core\entities\size\Products;
use core\entities\techno\Product;
use core\services\exports\Xml;
use core\services\size\Api;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class ParseJob extends BaseObject implements JobInterface
{


    /**
     * @param \yii\queue\Queue $queue
     * @throws \Exception
     */
    public function execute($queue)
    {

        $factor = Factor::find()->where(['key' => 'techno'])->one();
        $factor = $factor ? $factor->value : 1.3;


        \Yii::$app->settings->set('techno.is_job', true);
        $dir = \Yii::getAlias('@common/data/');
        $local = $dir . '/techno.zip';
        $xml = $dir . '/price.xml';

//        $connection = ftp_connect('mx1.technomarin.ru');
//        $auth = ftp_login($connection, 'info@west-marine.ru', '00270061');

        $connection = ftp_connect(\Yii::$app->settings->get('techno.host'));
        $auth = ftp_login($connection, \Yii::$app->settings->get('techno.login'), \Yii::$app->settings->get('techno.password'));
        ftp_pasv($connection, true);

        if ($auth && ftp_get($connection, $local, '/price.zip', FTP_BINARY)) {
            $zip = new \ZipArchive;
            $zip->open($local);
            $zip->extractTo($dir);
            $zip->close();

            $xml = simplexml_load_file($xml);

            $exporter = new Xml(['barcode', 'unit', 'storage', 'purchase', 'retail']);
            $exporter->addAttribute('ДатаСозданияТМ', (string)$xml['ДатаСоздания']);
            foreach ($xml->{'Товар'} as $item) {
                $product = new Product((string)$item->{'Артикул'}, $factor);
                $product->unit->set((string)$item->{'ЕдИзм'});
                $product->storage->set((string)$item->{'Остаток'});
                $product->purchase->set(str_replace(' ', '', (string)$item->{'ЦенаДилерская'}));
                $product->retail->set(str_replace(' ', '', (string)$item->{'ЦенаРозничная'}));

                $exporter->addProduct($product);
            }

            $exporter->save('@frontend/web/' . \Yii::$app->settings->get('techno.xml'));
        }
        \Yii::$app->settings->set('techno.date', time());
        \Yii::$app->settings->set('techno.is_job', false);
    }
}