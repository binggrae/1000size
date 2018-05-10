<?php


namespace console\controllers;


use core\entities\Factor;
use core\entities\size\Products;
use core\parsers\size\elements\Product;
use core\parsers\size\Parser;
use core\services\exports\Xml;
use core\services\XmlImport;
use yii\console\Controller;

class SizeController extends Controller
{

    /** @var Parser */
    private $parser;

    public function __construct(string $id, $module, Parser $parser, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->parser = $parser;
    }


    public function actionLoad()
    {
        $import = new XmlImport(\Yii::$app->settings->get('size.list'));
        $list = $import->getList();

        \Yii::$app->db->createCommand('UPDATE products SET status = :status', [
            ':status' => Products::STATUS_INACTIVE
        ])->execute();

        foreach ($list as $barcode) {
            $product = Products::find()->where(['barcode' => $barcode])->one();
            if (!$product) {
                $product = Products::create($barcode);

            } else {
                $product->status = Products::STATUS_ACTIVE;
            }

            $product->save();
        }

    }


    public function actionRun()
    {
        while (1) {
            $this->parser->run();
            sleep(60);
        }
    }


    public function actionSave()
    {

        $this->parser->save();
    }

}