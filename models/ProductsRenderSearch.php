<?php

namespace app\Models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProductsRender;

/**
 * ProductsRenderSearch represents the model behind the search form of `app\models\ProductsRender`.
 */
class ProductsRenderSearch extends ProductsRender
{

    public $has_nosubcategory;
    public $has_nocategory;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'active', 'has_nosubcategory', 'has_nocategory'], 'integer'],
            [['category', 'subcategory', 'name', 'short_description', 'description', 'articul', 'price', 'price_old', 'price_buy', 'lost', 'ei', 'images', 'short_seo', 'full_seo', 'title_page', 'meta_keywords', 'meta_description', 'url', 'brand', 'sizes', 'sizes_rus', 'color'], 'safe'],
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
        $query = ProductsRender::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'active' => $this->active,
        ]);

        if ($this->category) $query->andWhere(['like', 'category', $this->category]);
        if ($this->subcategory) $query->andWhere(['like', 'subcategory', $this->subcategory]);
        if ($this->has_nosubcategory) $query->andWhere(['IS', 'subcategory', NULL]);
        if ($this->has_nocategory) $query->andWhere(['IS', 'category', NULL]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'short_description', $this->short_description])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'articul', $this->articul])
            // ->andFilterWhere(['like', 'price', $this->price])
            // ->andFilterWhere(['like', 'price_old', $this->price_old])
            //  ->andFilterWhere(['like', 'price_buy', $this->price_buy])
            ->andFilterWhere(['like', 'lost', $this->lost])
            ->andFilterWhere(['like', 'ei', $this->ei])
            ->andFilterWhere(['like', 'images', $this->images])
            //->andFilterWhere(['like', 'short_seo', $this->short_seo])
            //  ->andFilterWhere(['like', 'full_seo', $this->full_seo])
            ->andFilterWhere(['like', 'title_page', $this->title_page])
            // ->andFilterWhere(['like', 'meta_keywords', $this->meta_keywords])
            // ->andFilterWhere(['like', 'meta_description', $this->meta_description])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'brand', $this->brand])
            ->andFilterWhere(['like', 'sizes', $this->sizes])
            ->andFilterWhere(['like', 'sizes_rus', $this->sizes_rus])
            ->andFilterWhere(['like', 'color', $this->color]);



        return $dataProvider;
    }
}
