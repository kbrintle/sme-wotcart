<?php

namespace common\models\customer\search;

use common\models\customer\CustomerActivity;
use common\models\customer\CustomerReward;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CostumerSearch represents the model behind the search form about `common\models\Customer`.
 */
class CustomerRewardSearch extends CustomerReward
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'customer_id'], 'integer'],
            [['points', 'created_at', 'order_id'], 'safe'],
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
    public function search($params, $id = null)
    {
        $query = CustomerReward::find()->orderBy(['created_at' => SORT_DESC])->alias('r')->select("r.*, o.order_id");
        $query->JoinWith(['order o']);

        if ($id) {
            $query->where(['r.customer_id' => $id]);
        }
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

        $query->andFilterWhere(['like', 'o.order_id', $this->order_id])
            ->andFilterWhere(['like', 'r.points', $this->points]);
        //->andFilterWhere(['like', 'type', $this->type]);

        //echo $query->createCommand()->getRawSql();die;

        return $dataProvider;
    }
}
