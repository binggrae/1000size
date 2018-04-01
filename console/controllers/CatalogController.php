<?php


namespace console\controllers;


use core\services\plans\ArCatalog;
use core\services\plans\Catalog;
use core\services\plans\MomCatalog;
use core\services\plans\PastelCatalog;
use yii\console\Controller;
use yii\helpers\Inflector;
use yii\helpers\Json;

class CatalogController extends Controller
{

    /**
     * @var Catalog
     */
    private $catalog;
    /**
     * @var MomCatalog
     */
    private $mamCatalog;
    /**
     * @var ArCatalog
     */
    private $arCatalog;
    /**
     * @var PastelCatalog
     */
    private $pastelCatalog;

    public function __construct(
        $id, $module,
        Catalog $catalog,
        MomCatalog $momCatalog,
        ArCatalog $arCatalog,
        PastelCatalog $pastelCatalog,
        array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->catalog = $catalog;
        $this->mamCatalog = $momCatalog;
        $this->arCatalog = $arCatalog;
        $this->pastelCatalog = $pastelCatalog;
    }

    public function actionParsePastel()
    {
        $this->pastelCatalog->parse();
    }

    public function actionParse()
    {
        $this->catalog->parse();
    }

    public function actionParseAr()
    {
        $this->arCatalog->parse();
    }

    public function actionParseMam()
    {
        $this->mamCatalog->parse();
    }


    public function actionReplace()
    {
        $path = \Yii::getAlias('@common/data/ar-catalog/json/');
        $dirs = scandir($path);

        foreach ($dirs as $dir) {
            if ($dir == '.' || $dir == '..') {
                continue;
            }

            $content = Json::decode(file_get_contents($path . $dir));

            $images = [];
            foreach ($content['images'] as $image) {
                $images[] = Inflector::transliterate($image);
            }
            $content['images'] = $images;

            $newPath = $path . Inflector::transliterate($dir);

            file_put_contents($newPath, Json::encode($content));

        }

    }

    public function actionReplaceImage()
    {
        $types = ['images/', 'plans/'];
        foreach ($types as $type) {
            $path = \Yii::getAlias('@common/data/ar-catalog/img/' . $type);
            $dirs = scandir($path);
            foreach ($dirs as $dir) {
                if ($dir == '.' || $dir == '..') {
                    continue;
                }

                rename($path . $dir, $path . Inflector::transliterate($dir));
            }
        }


    }

}