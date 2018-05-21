<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Products;
use app\utils\D;

/**
 * ProductsSearch represents the model behind the search form of `app\models\Products`.
 */
class ProductsSearch extends Products
{
    public $unique;
    public $id_maincategory;
    public $id_mainsubcategory;
    public $rendered;
    public $no_articul;
    public $images;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_source', 'id_brand', 'no_articul', 'status_source', 'images', 'convert_sizes_status', 'rendered', 'disactive', 'ei', 'id_category', 'id_subcategory', 'id_maincategory', 'id_mainsubcategory', 'dimension', 'unique'], 'integer'],
            [['color', 'id_in_source', 'name', 'short_description', 'description', 'articul', 'price', 'price_old', 'price_buy', 'lost', 'short_seo', 'full_seo', 'title_page', 'meta_keywords', 'meta_description', 'url', 'title_modification', 'name_setting', 'value', 'name_property_modification', 'value_property_modification'], 'safe'],
        ];
    }

    // these are statuses which meens exception in search

    public function Exceptions_array()
    {
        return [0, Products::BROKEN_CATEGORY, Products::MANUAL_CATEGORY];
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

        $query->from(['p' => Products::tableName()]);
        // присоединяем связи
        $query->joinWith(['source AS s']);
        $query->joinWith(['category AS c']);
        $query->joinWith(['subcategory AS subc']);
        $query->joinWith(['brand AS b']);
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
            'p.id' => $this->id,
            //  'p.status_source' => $this->status_source,
            'p.disactive' => $this->disactive,
            'p.ei' => $this->ei,
        ]);
        if ($this->no_articul) $query->andWhere(['IS', 'p.articul', NULL]);
        if (!in_array($this->id_maincategory, self::Exceptions_array())) {
            $query->andWhere(['p.id_maincategory' => $this->id_maincategory]);

//            $query->andFilterWhere(['OR',
//                    ['in', 'p.id_category', Categories::find()->select('id')->where(['id_maincategory' => $this->id_maincategory])->column()],
//                    ['p.manual_maincategory' => $this->id_maincategory],]
//            );
        } else {
            if ($this->id_maincategory == Products::MANUAL_CATEGORY) $query->andWhere(['subc.manual_category' => 1]);
            if ($this->id_maincategory == Products::BROKEN_CATEGORY) $query->andWhere(['p.id_maincategory' => Products::BROKEN_CATEGORY]);
        }

        if (!in_array($this->id_mainsubcategory, self::Exceptions_array())) {
            $query->andWhere(['p.id_mainsubcategory' => $this->id_mainsubcategory]);
//            $query->andFilterWhere(['OR',
//                ['in', 'p.id_subcategory', Subcategories::find()->select('id')->where(['id_mainsubcategory' => $this->id_mainsubcategory])->column()],
//                ['p.manual_mainsubcategory' => $this->id_mainsubcategory],
//            ]);
        } else {
            if ($this->id_mainsubcategory == Products::MANUAL_CATEGORY) $query->andWhere(['subc.manual_category' => 1]);
            if ($this->id_mainsubcategory == Products::BROKEN_CATEGORY) $query->andWhere(['p.id_mainsubcategory' => Products::BROKEN_CATEGORY]);

        }

        if ($this->id_brand) $query->andFilterWhere(['p.id_brand' => $this->id_brand]);
        if ($this->id_source) $query->andFilterWhere(['p.id_source' => $this->id_source]);
        if ($this->articul) $query->andFilterWhere(['p.articul' => $this->articul]);
        if ($this->dimension) $query->andFilterWhere(['p.dimension' => $this->dimension,]);
        if ($this->convert_sizes_status) $query->andFilterWhere(['p.convert_sizes_status' => $this->convert_sizes_status,]);
        if ($this->unique) $query->groupBy('p.articul,p.id_source');
        if ($this->rendered != 10) $query->andFilterWhere(['p.render_status' => $this->rendered]);
        if ($this->images == 1) $query->andWhere(["IS NOT", 'p.images', NULL]);
        if ($this->images == 2) $query->andWhere(['IS', 'p.images', NULL]);
        if ($this->status_source != 10) $query->andWhere(['p.status_source' => $this->status_source]);
        if ($this->name) $query->andWhere(['like', 'p.name', $this->name]);

        // не выводим товары которые не парсятся или не рендерятся
        $query->andWhere(['OR',
            ['c.not_parsing' => 0],
            ['IS', 'c.not_parsing', NULL]
        ]);
        $query->andWhere(['OR',
            ['subc.not_parsing' => 0],
            ['IS', 'subc.not_parsing', NULL]
        ]);

        //   $query->andWhere(['<>', 'c.not_render', 1]);
        //   $query->andWhere(['<>', 'subc.not_render', 1]);

        $exportQuery = clone  $query;
        $session = Yii::$app->session;
        $session->set('ids', $exportQuery->select('p.id')->column());
        // D::dump($session->get('ids'));

//        $query->andFilterWhere(['like', 'id_in_source', $this->id_in_source])
//            ->andFilterWhere(['like', 'name', $this->name])
//            ->andFilterWhere(['like', 'short_description', $this->short_description])
//            ->andFilterWhere(['like', 'description', $this->description])
//            ->andFilterWhere(['like', 'articul', $this->articul])
//            ->andFilterWhere(['like', 'price', $this->price])
//            ->andFilterWhere(['like', 'price_old', $this->price_old])
//            ->andFilterWhere(['like', 'price_buy', $this->price_buy])
//            ->andFilterWhere(['like', 'lost', $this->lost])
//            ->andFilterWhere(['like', 'images', $this->images])
//            ->andFilterWhere(['like', 'short_seo', $this->short_seo])
//            ->andFilterWhere(['like', 'full_seo', $this->full_seo])
//            ->andFilterWhere(['like', 'title_page', $this->title_page])
//            ->andFilterWhere(['like', 'meta_keywords', $this->meta_keywords])
//            ->andFilterWhere(['like', 'meta_description', $this->meta_description])
//            ->andFilterWhere(['like', 'url', $this->url])
//            ->andFilterWhere(['like', 'title_modification', $this->title_modification])
//            ->andFilterWhere(['like', 'id2', $this->id2])
//            ->andFilterWhere(['like', 'id_users', $this->id_users])
//            ->andFilterWhere(['like', 'name_setting', $this->name_setting])
//            ->andFilterWhere(['like', 'value', $this->value])
//            ->andFilterWhere(['like', 'name_property_modification', $this->name_property_modification])
//            ->andFilterWhere(['like', 'value_property_modification', $this->value_property_modification]);

        return $dataProvider;
    }
}
