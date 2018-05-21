<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Footsize;

/**
 * FootsizeSearch represents the model behind the search form of `app\models\Footsize`.
 */
class FootsizeSearch extends Footsize
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_brand', 'dimension'], 'integer'],
            [['uk', 'rus', 'eur', 'us', 'cm', 'cm2', 'dimension'], 'safe'],
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
        $query = Footsize::find();

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


        if ($this->dimension) $query->andWhere(['dimension' => $this->dimension]);
        if ($this->id_brand) $query->andWhere(['id_brand' => $this->id_brand]);
        if ($this->uk) $query->andWhere(['uk' => $this->uk]);
        if ($this->uk) $query->andWhere(['uk' => $this->uk]);
        if ($this->uk) $query->andWhere(['uk' => $this->uk]);

//        $query->andFilterWhere(['like', 'uk', $this->uk])
//            ->andFilterWhere(['like', 'uk', $this->rus])
//            ->andFilterWhere(['like', 'uk', $this->eur])
//            ->andFilterWhere(['like', 'us', $this->us])
//            ->andFilterWhere(['like', 'cm', $this->cm])
//            ->andFilterWhere(['like', 'cm2', $this->cm2]);

        return $dataProvider;
    }
}
