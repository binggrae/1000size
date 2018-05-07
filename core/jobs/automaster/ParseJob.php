<?php


namespace core\jobs\automaster;


use core\entities\automaster\Product;
use core\entities\Factor;
use core\services\exports\Xml;
use core\services\XmlImport;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class ParseJob extends BaseObject implements JobInterface
{

    public function execute($queue)
    {

        $factor = Factor::find()->where(['key' => 'auto'])->one();
        $factor = $factor ? $factor->value : 1.3;

        $xml = new XmlImport(\Yii::$app->settings->get('automaster.list'));
        $list = array_flip($xml->getList());

        $name = \Yii::$app->settings->get('automaster.name');
        $archive = \Yii::getAlias('@ftp/prov1/' . $name . '.zip');
        $runtime = \Yii::getAlias('@runtime/ftp/');

        $zip = new \ZipArchive;
        $zip->open($archive);
        $zip->extractTo($runtime);
        $zip->close();

        $zip = new \ZipArchive();

        $exporter = new Xml(['barcode', 'title', 'unit', 'storage', 'purchase', 'retail', 'brand']);
        if ($zip->open($runtime . $name . '.xlsx') == true) {
            foreach ($this->getData($zip) as $data) {
                if (isset($list[$data['B']])) {
                    $product = new Product($data['B'], $factor);
                    $product->title->set($data['C']);
                    $product->unit->set($data['H']);
                    $product->storage->set($data['G']);
                    $product->purchase->set($data['F']);
                    $product->brand->set($data['A']);

                    $exporter->addProduct($product);
                }
            }
            $zip->close();
            $exporter->save('@frontend/web/' . \Yii::$app->settings->get('automaster.xml'));

            \Yii::$app->settings->set('automaster.date', time());
        }
    }


    /**
     * @param \ZipArchive $zip
     * @return \Generator
     */
    private function getData($zip)
    {
        $xml = simplexml_load_string($zip->getFromName('xl/sharedStrings.xml'));

        $values = [];
        foreach ($xml->children() as $item) {
            $values[] = (string)$item->t;
        }

        $xml = simplexml_load_string($zip->getFromName('xl/worksheets/sheet1.xml'));

        $head = true;
        foreach ($xml->sheetData->row as $item) {
            if ($head) {
                $head = false;
                continue;
            }
            $data = [];
            foreach ($item as $child) {
                $value = isset($child->v) ? (string)$child->v : false;
                $value = $child['t'] == 's' ? $values[$value] : $value;

                $data[((string)$child['r'])[0]] = $value;
            }
            yield $data;
        }
    }

}