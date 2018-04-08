<?php


namespace core\parsers\east_catalog;


use core\entities\east\catalog\Equipment;
use core\entities\east\catalog\EquipmentUnit;
use core\entities\east\catalog\Part;
use core\entities\east\catalog\Power;
use core\entities\east\catalog\Unit;
use core\parsers\east_catalog\elements\Category;
use core\parsers\east_catalog\pages\EquipmentPage;
use core\parsers\east_catalog\pages\PartPage;
use core\parsers\east_catalog\pages\PowerPage;
use core\parsers\east_catalog\pages\UnitPage;
use core\services\Client;

class Parser
{

    const URL = 'http://base.eastmarine.ru/';

    /** @var Client */
    private $client;

    /** @var Category[] */
    private $categories = [];

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->categories[] = new Category('catalog/14');
    }


    public function run()
    {

        foreach ($this->categories as $category) {
            foreach ($this->getPowers($category) as $power) {
                $power->save();
                if($power->id < 15) {
                    continue;
                }
                foreach ($this->getEquipment($power) as $i => $equipment) {
                    $equipment->power_id = $power->id;
                    $equipment->save();
                    if($equipment->id < 949) {
                        continue;
                    }


                    $units = $this->getUnit($equipment);
                    foreach ($units as $unit) {
                        $unit->attachTo($equipment);
                    }

                    foreach ($this->getPart($units) as $part) {
                        $part->attachTo($part->unit);
                    }

                    var_dump('#' . $i . ' Equipment: ' . $equipment->id . '. date: ' . date('H:i:s'));

                }
                var_dump('================= Power:' . $power->id . '. date: ' . date('H:i:s'));
            }
        }
    }

    /**
     * @param Category $category
     * @return Power[]|\Generator
     */
    private function getPowers(Category $category)
    {
        $response = $this->client->get($category->getUrl())->send();
        $page = new PowerPage($response->content);
        foreach ($page->getList() as $item) {
            yield $item;
        }
        $page->close();
    }

    /**
     * @param Power $power
     * @return Equipment[]|\Generator
     */
    private function getEquipment(Power $power)
    {
        $response = $this->client->get($power->getUrl())->send();
        $page = new EquipmentPage($response->content);
        foreach ($page->getList() as $item) {
            yield $item;
        }
        $page->close();
    }

    /**
     * @param Equipment $equipment
     * @return Unit[]
     */
    private function getUnit(Equipment $equipment)
    {
        $response = $this->client->get($equipment->getUrl())->send();
        $page = new UnitPage($response->content);
        $units = [];
        foreach ($page->getList() as $item) {
            $units[] = $item;
        }
        $page->close();
        return $units;
    }

    /**
     * @param Unit[] $units
     * @return Part[]|\Generator
     * @throws \yii\httpclient\Exception
     */
    private function getPart(array $units)
    {
        $link = [];
        foreach ($units as $unit) {
            $link[] = $unit->getUrl();
        }
        $chunks = array_chunk($link, 10, 1);

        foreach ($chunks as $j => $chunk) {
            var_dump("Load chunk {$j} in " . count($chunks) . " : " . count($chunk));
            $requests = [];
            foreach ($chunk as $i => $link) {
                $requests[$i] = $this->client->get($link);
            }

            $responses = $this->client->batch($requests);
            foreach ($responses as $i => $response) {
                if(!$response->isOk) {
                    var_dump($response->getStatusCode());
                }
                $page = new PartPage($response->content);
                foreach ($page->getList() as $item) {
                    $item->unit = $units[$i];
                    yield $item;
                }

                $page->close();
            }
        }
    }
}