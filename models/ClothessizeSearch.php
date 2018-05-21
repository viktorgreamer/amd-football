<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Clothessize;

/**
 * ClothessizeSearch represents the model behind the search form of `app\models\Clothessize`.
 */
class ClothessizeSearch extends Clothessize
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','id_brand', 'dimension'], 'integer'],
            [['mark', 'size', 'rus', 'growth', 'width', 'height', 'dimension'], 'safe'],
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
        $query = Clothessize::find();

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
            'id_brand' => $this->id_brand,
            'dimension' => $this->dimension,
        ]);

        $query->andFilterWhere(['like', 'mark', $this->mark])
            ->andFilterWhere(['like', 'size', $this->size])
            ->andFilterWhere(['like', 'rus', $this->rus])
            ->andFilterWhere(['like', 'growth', $this->growth])
            ->andFilterWhere(['like', 'width', $this->width])
            ->andFilterWhere(['like', 'height', $this->height]);

        return $dataProvider;
    }
}
