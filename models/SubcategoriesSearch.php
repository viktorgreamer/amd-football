<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Subcategories;

/**
 * SubcategoriesSearch represents the model behind the search form of `app\models\Subcategories`.
 */
class SubcategoriesSearch extends Subcategories
{
    public $id_source;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_category', 'link', 'id_source'], 'integer'],
            [['name'], 'safe'],
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
        $query = Subcategories::find();

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


      if ($this->id_source) $query->andFilterWhere(['in', 'id_category', Categories::getCategories($this->id_source)]);

       if ($this->id_category) $query->andFilterWhere(['id_category' => $this->id_category]);

        return $dataProvider;
    }
}
