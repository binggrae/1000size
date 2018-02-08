<?php


namespace core\services\plans;



use core\services\Client;
use yii\helpers\Json;
use yii\helpers\VarDumper;

class Catalog
{

    private $url = 'http://www.mgprojekt.ru/';

    /*
     1:"плоская",
	2:"двускатная",
	3:"четырехскатная (вальмовая)",
	4:"ломанная "
     */
    private $roof = [
        [
            'link' => 'http://www.mgprojekt.ru/proekty-domov-s-ploskoy-kryshey',
            'id' => 1,
        ], [
            'link' => 'http://www.mgprojekt.ru/proekty-domov-s-dwuskatnoy-kryshey',
            'id' => 2,
        ], [
            'link' => 'http://www.mgprojekt.ru/proekty-domov-s-mnogoskatnoy-kryshey',
            'id' => 3,
        ], [
            'link' => 'http://www.mgprojekt.ru/proekty-domov-so-sbornym-karkasom-kryshi',
            'id' => 4,
        ]
    ];

    /*
     1:"одноэтажные",
	2:"двухэтажные",
	3:"трехэтажные",
	4:"мансарда"

     */
    private $floors = [
        [
            'link' => 'http://www.mgprojekt.ru/proekty-odnoetazhnyh-domov',
            'id' => 1,
        ], [
            'link' => 'http://www.mgprojekt.ru/proekty-dwuhetazhnyh-domov',
            'id' => 2,
        ], [
            'link' => 'http://www.mgprojekt.ru/proekty-domov-s-mansardoy',
            'id' => 4,
        ]

    ];


    private $garages = [
        [
            'link' => 'http://www.mgprojekt.ru/proekty-domov-s-garazhom',
            'id' => 6,
        ], [
            'link' => 'http://www.mgprojekt.ru/proekty-domov-s-dwoynym-garazhom',
            'id' => 7,
        ]
    ];


    private $material_id = 12;
    private $dacha_id = 4;
    private $house_id = 1;
    private $cottage_id = 3;

    private $materials = [
        [
            'link' => 'http://www.mgprojekt.ru/proekty-derewiannyh-domov',
            'id' => 1
        ]
    ];


    private $data = [];

    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }


    public function parse()
    {

        $request = $this->client->get('http://www.mgprojekt.ru/wszystkie-projekty?limit=0&current_page=1')->send();
        $pq = \phpQuery::newDocumentHTML($request->content);

        foreach ($pq->find('.picture') as $item) {
            $pagePq = pq($item);
            $this->data[$pagePq->attr('href')] = [
                'name' => $pagePq->attr('title'),
                'description' => "описание",
                'width' => 0, //ширина
                'length' => 0, //длина
                'area' => 0, //площадь
                'garage' => 0, //гараж на кол-во машин(0-гаража нет)
                'beds' => null, //кол-во спален
                'images' => [],
                'plans' => [],
                'materials' => [], //дерево
                'extentions' => [], //гараж
                'options' => [],
                'style' => [],
                'type' => [], // дерево
                'purpose' => [],
                'floors' => [],
                'roof' => [],
            ];
        }

        $this->loadRoof();
        $this->loadFloors();
        $this->loadGarage();
        $this->loadMaterials();

        $i = 0;
        foreach ($this->data as $link => &$item) {
            $i++;
            VarDumper::dump("Load {$i} from " . count($this->data));
            $request = $this->client->get($this->url . $link)->send();
            $pq = \phpQuery::newDocumentHTML($request->content);
            $item['description'] = trim($pq->find('div[itemprop="description"]')->text());

            preg_match('/ширина - ([\d\,]+) м<\/li>/u', $request->content, $math);
            $item['width'] = isset($math[1]) ? (float)str_replace(',', '.', $math[1]) : null;

            preg_match('/длина - ([\d\,]+) м<\/li>/u', $request->content, $math);
            $item['length'] = isset($math[1]) ? (float)str_replace(',', '.', $math[1]) : null;

            preg_match('/общая площадь - ([\d\,]+) кв.м<\/li>/u', $request->content, $math);
            $item['area'] = isset($math[1]) ? (float)str_replace(',', '.', $math[1]) : null;

            if ($item['extentions']) {
                $item['garage'] = 1;
            }


            if ($item['materials'][0] == $this->material_id) {
                $item['purpose'] = [4, 6];
            } else {
                if ($item['area'] < 60) {
                    $item['purpose'] = [1, 2, 4, 6, 7];
                } else {
                    $item['purpose'] = [2, 4, 6];
                }
            }

            $item['type'][] = $item['area'] < 300 ? $this->house_id : $this->cottage_id;

            $basePath = \Yii::getAlias('@common/data/catalog');
            $imageDir = '/img/images/' . $link . '/';
            if (!file_exists($basePath . $imageDir)) {
                mkdir($basePath . $imageDir);
            }

            $planDir = '/img/plans/' . $link . '/';
            if (!file_exists($basePath . $planDir)) {
                mkdir($basePath . $planDir);
            }
            foreach ($pq->find('#thumb-slider a') as $thumbs) {
                $id = $imageDir . count($item['images']) . '.jpg';
                file_put_contents($basePath . $id, file_get_contents('http://www.mgprojekt.ru/galleries_mirror,index,id,' . pq($thumbs)->attr('rel') . '.html?prefix=-1'));
                $item['images'][] = '..' . $id;
            }

            $html = $pq->find('.tab.elevations')->html();
            $html = str_replace('<!--', '', $html);
            $html = str_replace('-->', '', $html);

            $elevations = \phpQuery::newDocumentHTML($html);
            foreach ($elevations->find('.galleries_mirrored') as $thumb) {
                $href = pq($thumb)->attr('href');
                if (substr($href, 0, 4) == 'http') {
                    continue;
                }
                $id = $imageDir . count($item['images']) . '.jpg';
//                file_put_contents($basePath . $id, file_get_contents('http://www.mgprojekt.ru/' . pq($thumb)->attr('href')));
                $item['images'][] = '..' . $id;
            }

            $html = $pq->find('.tab.projections')->html();
            $html = str_replace('<!--', '', $html);
            $html = str_replace('-->', '', $html);

            $projections = \phpQuery::newDocumentHTML($html);
            foreach ($projections->find('.galleries_mirrored') as $thumb) {
                $href = pq($thumb)->attr('href');
                if (substr($href, 0, 4) == 'http') {
                    continue;
                }
                $id = $planDir . count($item['plans']) . '.jpg';
//                file_put_contents($basePath . $id, file_get_contents('http://www.mgprojekt.ru/' . pq($thumb)->attr('href')));
                $item['plans'][] = '..' . $id;
            }
            VarDumper::dump($item['name']);
            VarDumper::dump($item['floors']);
            file_put_contents(\Yii::getAlias('@common/data/catalog/json/' . $link . '.json'), Json::encode($item));

        }
        unset($item);
    }

    private function loadMaterials()
    {
        foreach ($this->materials as $material) {
            $request = $this->client->get($material['link'] . '?limit=0&current_page=1')->send();
            $pq = \phpQuery::newDocumentHTML($request->content);

            foreach ($pq->find('.picture') as $item) {
                $href = pq($item)->attr('href');
                $this->data[$href]['materials'][] = $material['id'];
                $this->data[$href]['type'][] = $this->dacha_id;
            }
        }

        foreach ($this->data as &$item) {
            if (empty($item['materials'])) {
                $item['materials'][] = $this->material_id;
            }
        }
        unset($item);
    }

    private function loadGarage()
    {
        foreach ($this->garages as $garage) {
            $request = $this->client->get($garage['link'] . '?limit=0&current_page=1')->send();
            $pq = \phpQuery::newDocumentHTML($request->content);

            foreach ($pq->find('.picture') as $item) {
                $this->data[pq($item)->attr('href')]['extentions'][] = $garage['id'];
            }
        }
    }

    private function loadRoof()
    {
        foreach ($this->roof as $category) {
            $request = $this->client->get($category['link'] . '?limit=0&current_page=1')->send();
            $pq = \phpQuery::newDocumentHTML($request->content);

            foreach ($pq->find('.picture') as $item) {
                $this->data[pq($item)->attr('href')]['roof'][] = $category['id'];
            }
        }
    }

    private function loadFloors()
    {
        foreach ($this->floors as $floor) {
            $request = $this->client->get($floor['link'] . '?limit=0&current_page=1')->send();
            $pq = \phpQuery::newDocumentHTML($request->content);

            foreach ($pq->find('.picture') as $item) {
                if ($floor['id'] == 4) {
                    if ($this->data[pq($item)->attr('href')]['floors'][0] == 2) {
                        $this->data[pq($item)->attr('href')]['floors'][0] = 4;
                    } elseif ($this->data[pq($item)->attr('href')]['floors'][0] == 1) {
                        $this->data[pq($item)->attr('href')]['floors'][0] = 1;
                    } else {
                        $this->data[pq($item)->attr('href')]['floors'][] = $floor['id'];
                    }
                } else {
                    $this->data[pq($item)->attr('href')]['floors'][] = $floor['id'];
                }
            }
        }
    }


}