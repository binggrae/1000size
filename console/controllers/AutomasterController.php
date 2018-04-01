<?php


namespace console\controllers;


use core\entities\automaster\Product;
use core\services\Xml;
use core\jobs\automaster\ParseJob;
use core\services\XmlImport;
use yii\console\Controller;

class AutomasterController extends Controller
{

    public function actionTest()
    {
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

        if ($zip->open($runtime . $name . '.xlsx') == true) {
            $products = [];
            foreach ($this->getData($zip) as $data) {
                if(isset($list[$data['B']])) {
                    $products[] = new Product(
                        $data['B'],
                        $data['C'],
                        $data['H'],
                        $data['G'],
                        $data['F'],
                        $data['A']
                    );
                }

            }
            $zip->close();

            $xmlService = new Xml($products);
            $xmlService->generate();
            $xmlService->save('@frontend/web/' . \Yii::$app->settings->get('automaster.xml'));

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

    public function actionRun()
    {
        \Yii::$app->queue->push(new ParseJob());
    }
}