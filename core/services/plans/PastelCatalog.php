<?php


namespace core\services\plans;


use core\services\Client;
use yii\httpclient\Response;

class PastelCatalog
{

    const URL = 'http://artpostel-msk.ru';

    /** @var Client */
    private $client;

    private $categories = [];

    private $items = [];

    private $filters = [];

    private $codes = [];

    public function __construct(Client $client)
    {
        $this->client = $client;

        $this->filters['byaz'] = 'Бязь';
        $this->filters['poplin'] = 'Поплин';
        $this->filters['satin'] = 'Сатин';
        $this->filters['pestrotkanyj-satin'] = 'Пестротканый сатин';
        $this->filters['satin-zhakkard'] = 'Сатин - жаккард';
        $this->filters['velyur'] = 'Велюр';
    }

    public function parse()
    {

        $this->loadCategories();

        $this->loadItems();

        $this->loadProperties();

        var_dump(count($this->items));


        $handle = fopen(\Yii::getAlias('@common/data/result.csv'), 'w');
        $titles = [
            'sku',
            'name',
            'category',
            'price',
            'sku:size:color:price:purchaseprice:amount',
            'description',
            'photos',
            'properties',
        ];
        fputcsv($handle, $titles, ';');


        foreach ($this->loadData() as $id => $data) {
            var_dump($id);
            fputcsv($handle, $data, ';');
        }
        fclose($handle);

    }

    private function loadData()
    {
        $chunks = array_chunk($this->items, 20, true);
        $index = 10000;

        foreach ($chunks as $chunk) {
            $requests = [];
            foreach ($chunk as $id => $item) {
                $requests[$id] = $this->client->get(self::URL . $item['link']);
            }

            /** @var Response[] $responses */
            $responses = $this->client->batch($requests);

            foreach ($responses as $id => $response) {
                $pq = \phpQuery::newDocumentHTML($response->content);
                try {
                    $item = [
                        'sku' => $index++,
                        'name' => $this->toWindow($pq->find('h1')->text()),
                        'category' => $this->toWindow($this->getCategory($id)),
                        'price' => $this->getPrice($pq),
                        'sku:size:color:price:purchaseprice:amount' => $this->toWindow($this->getCalculated($pq, $id)),
                        'description' => $this->toWindow($this->getDescription($pq)),
                        'photos' => $this->toWindow($this->getPhotos($pq)),
                        'properties' => $this->toWindow($this->getProp($id)),
                    ];
                } catch (\Exception $e) {
                    file_put_contents(\Yii::getAlias('@common/data/' . uniqid() . '.html'), $response->content);
                    throw $e;
                }

                if ($item['sku:size:color:price:purchaseprice:amount']) {
                    yield $item;
                }
            }
        }
    }

    private function toWindow($text)
    {
//         return $text;
        return iconv("utf-8", "windows-1251", $text);
    }


    /**
     * @param \phpQueryObject $pq
     * @return integer
     */
    private function getPrice($pq)
    {
        foreach ($pq->find('.price-block .add-on:eq(0)') as $item) {
            $price = (integer)pq($item)->text();
            if (is_integer($price)) {
                return $price;
            }
        }

        return 0;
    }


    /**
     * @param \phpQueryObject $pq
     * @return string
     */
    private function getDescription($pq)
    {
        $str = $pq->find('.description')->html();
        $str = str_replace("\n", "", $str);
        $str = str_replace("\r", "", $str);
        $str = str_replace("\t", "", $str);
        $str = str_replace("\"", "'", $str);

        return $str;
    }

    private function getProp($id)
    {
        $props = [];
        foreach ($this->items[$id]['prop'] as $prop) {
            $props[] = 'Материал:' . $this->filters[$prop];
        }

        return implode(';', $props);
    }

    /**
     * @param \phpQueryObject $pq
     * @return string
     */
    private function getCalculated($pq, $id)
    {
        $items = [];
        foreach ($pq->find('.table tr') as $tr) {
            $pqTr = pq($tr);

            $price = (integer)$pqTr->find('.price-block .add-on:eq(0)')->text();
            $size = explode('<br>', $pqTr->find('.articul')->html());
            $size = count($size) > 1 ? $size[1] : $size[0];

            $sku = trim($pqTr->find('.articul b')->text());
            $this->codes[$sku][$id] = $id;

            $count = count($this->codes[$sku]);
            $prefix = $count > 1 ? '-' . ($count - 1) : '';

            $item = [
                $sku . $prefix,
                $size,
                '',
                $price,
                round($price / 2),
                500
            ];

            $items[] = '[' . implode(':', $item) . ']';

        }

        return implode(';', $items);
    }

    /**
     * @param \phpQueryObject $pq
     * @return string
     */
    private function getPhotos($pq)
    {
        $img = [];
        $img[] = self::URL . $pq->find('.help-block a')->attr('href');

        foreach ($pq->find('.thumbnails a') as $item) {
            $img[] = self::URL . pq($item)->attr('href');
        }

        return implode(';', $img);
    }

    private function getCategory($id)
    {
        $result = [];
        $item = $this->items[$id];
        foreach ($item['categories'] as $category) {
            $result[] = '[' . $this->categories[$category]['root'] . ']';
            $result[] = '[' . $this->categories[$category]['root'] . '>>' . $this->categories[$category]['title'] . ']';
        }

        return implode(',', $result);
    }


    private function loadProperties()
    {
        foreach ($this->filters as $prop => $filter) {
            $response = $this->client->get(self::URL . '/tag/' . $prop . '/')->send();
            $pq = \phpQuery::newDocumentHTML($response->content);

            foreach ($pq->find('#datatable > tbody > tr') as $item) {
                $id = pq($item)->find('> td:eq(2) > span')->attr('id');
                if (isset($this->items[$id])) {
                    $this->items[$id]['prop'][] = $prop;
                }
            }
        }
    }


    private function loadItems()
    {
        $chunks = array_chunk($this->categories, 10, true);

        foreach ($chunks as $chunk) {
            $requests = [];
            foreach ($chunk as $url => $category) {
                $requests[$url] = $this->client->get(self::URL . $url);
            }

            /** @var Response[] $responses */
            $responses = $this->client->batch($requests);

            foreach ($responses as $category => $response) {
                var_dump('load category: ' . $category);
                $pq = \phpQuery::newDocumentHTML($response->content);
                foreach ($pq->find('.thumbnail h4') as $item) {
                    $id = pq($item)->attr('id');
                    $url = pq($item)->find('a')->attr('href');
                    if (!isset($this->items[$id])) {
                        $this->items[$id] = [
                            'id' => $id,
                            'link' => $url,
                            'categories' => [],
                            'prop' => [],
                        ];
                    }

                    $this->items[$id]['categories'][] = $category;
                }
            }
        }
    }

    private function loadCategories()
    {
        $request = $this->client->get(self::URL)->send();
        $pq = \phpQuery::newDocumentHTML($request->content);

        foreach ($pq->find('.well > .nav > .nav-header') as $item) {
            $title = pq($item)->find(' > a')->text();

            foreach (pq($item)->find('.nav a') as $link) {
                $pqLink = pq($link);
                $url = $pqLink->attr('href');

                $this->categories[$url] = [
                    'root' => $title,
                    'title' => $pqLink->text(),
                    'link' => $url,
                ];
            }
        }
    }


}