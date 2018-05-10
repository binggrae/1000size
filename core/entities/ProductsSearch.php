<?php

namespace core\entities;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\entities\size\Products;

/**
 * ProductsSearch represents the model behind the search form of `core\entities\size\Products`.
 */
class ProductsSearch extends Products
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'storageM', 'storageV', 'purchase', 'retail', 'load_ts'], 'integer'],
            [['link', 'barcode', 'title', 'unit', 'brand', 'country'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Products::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'load_ts' => SORT_DESC,
                ]
            ],
            'pagination' => [
                'pageSize' => 100
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'storageM' => $this->storageM,
            'storageV' => $this->storageV,
            'purchase' => $this->purchase,
            'retail' => $this->retail,
            'load_ts' => $this->load_ts,
        ]);

        $query->andFilterWhere(['like', 'link', $this->link])
            ->andFilterWhere(['like', 'barcode', $this->barcode])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'unit', $this->unit])
            ->andFilterWhere(['like', 'brand', $this->brand])
            ->andFilterWhere(['like', 'country', $this->country]);

        return $dataProvider;
    }
}
