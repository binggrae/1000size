<?php


namespace core\services\plans;


use core\services\Client;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\Json;

class ArCatalog
{

    private $categories;

    private $url = 'http://arplans.ru';


    private $mansarda = [];

    private $structure = [
        'name' => '',
        'description' => '',
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

    private $basePath;

    private $data = [];

    /**
     * @var Client
     */
    private $client;

    private $materialDep = [
        'блок' => 'doma-kamen',
        'пеноблок' => 'doma-kamen',
        'бревно' => 'doma-brevno',
        'брус' => 'doma-brus',
        'каркас' => 'doma-karkas',
    ];

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->basePath = \Yii::getAlias('@common/data/ar-catalog');

        $this->categories = [
            'doma-kamen' => [
                'link' => 'http://arplans.ru/product-category/doma-kamen/',
                'links' => [],
                'materials' => [8, 10, 11, 12, 18, 22],
                'types' => [
                    [0, 300, [1, 3]],
                    [300, 9999, [1, 3, 6]]
                ],
                'purposes' => [
                    [0, 9999, [4, 6]],
                ],
            ],

            'doma-brevno' => [
                'link' => 'http://arplans.ru/product-category/doma-brevno/',
                'links' => [],
                'materials' => [6, 33, 2],
                'types' => [
                    [0, 100, [1, 4]],
                    [100, 300, [1, 3]],
                    [300, 9999, [6]]
                ],
                'purposes' => [
                    [0, 60, [1, 2, 4, 6, 7]],
                    [60, 9999, [2, 4, 6]],
                ],
            ],

            'doma-brus' => [
                'link' => 'http://arplans.ru/product-category/doma-brus/',
                'links' => [],
                'materials' => [1, 3, 4, 5, 20],
                'types' => [
                    [0, 100, [1, 4]],
                    [100, 300, [1, 3]],
                    [300, 9999, [6]]
                ],
                'purposes' => [
                    [0, 60, [1, 2, 4, 6, 7]],
                    [60, 9999, [2, 4, 6]],
                ],
            ],

            'doma-karkas' => [
                'link' => 'http://arplans.ru/product-category/doma-karkas/',
                'links' => [],
                'materials' => [24, 25, 26, 27],
                'types' => [
                    [0, 300, [1, 3]],
                    [300, 9999, [1, 3, 6]]
                ],
                'purposes' => [
                    [0, 9999, [4, 6]],
                ],
            ],

            'kombi' => [
                'link' => 'http://arplans.ru/product-category/kombi/',
                'links' => [],
                'materials' => null,
                'types' => [
                    [0, 300, [1, 3]],
                    [300, 9999, [1, 3, 6]]
                ],
                'purposes' => [
                    [0, 9999, [4, 6]],
                ],
            ],

            'taunhaus' => [
                'link' => 'http://arplans.ru/product-category/taunhaus/',
                'links' => [],
                'materials' => null,
                'types' => [
                    [0, 300, [1, 3]],
                    [300, 9999, [1, 3, 6]]
                ],
                'purposes' => [
                    [0, 9999, [4, 6]],
                ],
            ],

            'bani' => [
                'link' => 'http://arplans.ru/product-category/bani/',
                'links' => [],
                'materials' => null,
                'types' => [
                    [0, 9999, [2]],
                ],
                'purposes' => [
                    [0, 9999, [4, 6]],
                ],
            ]
        ];
    }

    private $names = [];


    public function parse()
    {

        foreach ($this->categories as $cat_id => &$category) {
            var_dump($cat_id);

            $this->loadCategory($category);

            $i = 0;
            foreach ($category['links'] as $chunk) {
                $i++;
                print_r("Load {$i} in " . count($category['links']) . "\n");

                $requests = [];
                foreach ($chunk as $link) {
                    $id = explode('/', preg_replace('/\/$/', '', $link));
                    $id = urldecode($id[count($id) - 1]);
                    $id = Inflector::transliterate($id);
                    $requests[$id] = $this->client->get($link);
                }


                $responses = $this->client->batch($requests);
                var_dump('Loaded');

                foreach ($responses as $id => $response)
                {
                    $pq = \phpQuery::newDocumentHTML($response->content);
                    $descHtml = trim($pq->find('.description p')->html());
                    $descHtml = preg_replace('!\s++!u', ' ', $descHtml);
                    $propHtml = $pq->find('.catalog-project__properties tbody')->html();
                    $propHtml = preg_replace('!\s++!u', ' ', $propHtml);
                    $tags = $pq->find('.tagged_as a');


                    $item = $this->structure;
                    $item['name'] = $this->getName($pq);
                    $item['price'] = $this->getPrice($pq);
                    $item['description'] = $this->getDescription($pq);
                    list($item['width'], $item['length']) = $this->getSize($descHtml);
                    $item['area'] = $this->getArea($descHtml);
                    $item['garage'] = $this->getGarage($propHtml);
                    $item['materials'] = $this->getMaterials($category, $descHtml);
                    $item['extentions'] = $this->getExtentions($item['garage']);
                    $item['options'] = $this->getOptions($propHtml);
                    $item['type'] = $this->getType($category, $item);
                    $item['floors'] = $this->getFloors($descHtml, $tags);
                    $item['purpose'] = $this->getPurpose($category, $item);

                    list($item['images'], $item['plans']) = $this->getImg($id, $pq);

                    $this->names[] = $item['name'] . "\r";
                    file_put_contents($this->basePath . '/json/' . $id . '.json', Json::encode($item));
                }
            }
            file_put_contents($this->basePath . '/project.txt', $this->names);
        }
    }


    /**
     * @param \phpQueryObject $pq
     * @return float
     */
    private function getPrice($pq)
    {
        return (float)str_replace(' ', '', $pq->find('.summary .price')->text());
    }

    /**
     * @param $id
     * @param \phpQueryObject $pq
     * @return array
     */
    private function getImg($id, $pq)
    {
        $result = [
            'plans' => [
                'dir' => '/img/plans/' . $id . '/',
                'items' => [],
            ],
            'images' => [
                'dir' => '/img/images/' . $id . '/',
                'items' => [],
            ]
        ];

        foreach ($result as $item) {
            if (!file_exists($this->basePath . $item['dir'])) {
                //mkdir($this->basePath . $item['dir']);
            }
        }

        $images = $pq->find('.thumb-slider .slide img');

        foreach ($images as $image) {
            $imgPq = pq($image);

            $url = $imgPq->attr('srcset');
            if (!$url) {
                continue;
            }

            $key = preg_match('/план/u', $imgPq->attr('alt')) ? 'plans' : 'images';

            $name = $result[$key]['dir'] . count($result[$key]['items']) . '.jpg';
            $result[$key]['items'][] = '..' . $name;


            $thumbs = explode(', ', $url);
            $size = [];
            foreach ($thumbs as $thumb) {
                preg_match('/ (\d+)w/', $thumb, $math);
                $size[$math[1]] = explode(' ', $thumb)[0];

            }
            ksort($size);

            $url = array_pop($size);

            //file_put_contents($this->basePath . $name, file_get_contents($url));
        }

        return [$result['images']['items'], $result['plans']['items']];
    }


    private function getPurpose($category, $item)
    {
        foreach ($category['purposes'] as $type) {
            if ($type[0] <= $item['area'] && $item['area'] < $type[1]) {
                return $type[2];
            }
        }
        return [];
    }

    private function getFloors($html, $tags)
    {
        preg_match("/Количество этажей: ([\d]+)/u", $html, $math);
        $floor = isset($math[1]) ? $math[1] : 1;

        if ($floor == 2) {
            foreach ($tags as $tag) {
                if (pq($tag)->text() == "дома с мансардой") {
                    $floor = 4;
                }
            }
        }

        return [$floor];
    }


    private function getType($category, $item)
    {
        foreach ($category['types'] as $type) {
            if ($type[0] <= $item['area'] && $item['area'] < $type[1]) {
                return $type[2];
            }
        }
        return [];
    }

    private function getOptions($html)
    {
        $return = [];
        if (preg_match('/class="p-name">Балкон<\/td>/', $html)) {
            $return[] = 4;
        }
        if (preg_match('/class="p-name">Терраса<\/td>/', $html)) {
            $return[] = 5;
        }

        return $return;
    }

    private function getExtentions($garage)
    {
        return $garage ? [6] : [];
    }

    private function getMaterials($category, $html)
    {
        if ($category['materials']) {
            return $category['materials'];
        } else {
            $result = [];
            foreach ($this->materialDep as $key => $item) {
                if (preg_match('/' . $key . '/u', $html)) {
                    $result = ArrayHelper::merge($result, $this->categories[$item]['materials']);
                }
            }
            return $result;
        }
    }


    /**
     * @param $html
     * @return false|int
     */
    private function getGarage($html)
    {
        return preg_match('/class="p-name">Гараж<\/td>/', $html);
    }


    /**
     * @param $html
     * @return float|int
     */
    private function getArea($html)
    {
        preg_match("/Площадь: ([\d\.]+)/u", $html, $math);

        return isset($math[1]) ? (float)$math[1] : 0;
    }


    /**
     * @param $html
     * @return array
     */
    private function getSize($html)
    {
        preg_match('/Габариты дома: ([\d\.]+)х([\d\.]+)/', $html, $math);
        if (count($math) == 3) {
            return [
                (float)$math[1],
                (float)$math[2],
            ];
        }
//        die();
        return [0, 0];
    }

    /**
     * @param \phpQueryObject $pq
     * @return mixed
     */
    private function getDescription($pq)
    {
        return trim($pq->find('.woocommerce-Tabs-panel--description >strong')->text());

    }


    /**
     * @param \phpQueryObject $pq
     * @return string
     */
    private function getName($pq)
    {
        $name = trim(explode('(', $pq->find('.product_title ')->text())[0]);

        return $name;
    }


    private function loadCategory(&$category)
    {

        $url = $category['link'] . '?pager=all';


        $request = $this->client->get($url)->send();
        $pq = \phpQuery::newDocumentHTML($request->content);

        $links = [];
        foreach ($pq->find('.read_more_link') as $item) {
            $links[] = pq($item)->attr('href');
        }
        $category['links'] = array_chunk($links, 10);
    }

}