<?php

namespace common\models\customer\search;

use common\models\customer\CustomerActivity;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CostumerSearch represents the model behind the search form about `common\models\Customer`.
 */
class CustomerActivitySearch extends CustomerActivity
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_activity_id', 'customer_id'], 'integer'],
            [['customer_activity_id', 'customer_id', 'key', 'data', 'ip', 'created_at'], 'safe'],
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
        $query = CustomerActivity::find()->orderBy(['created_at' => SORT_DESC]);

        if ($id) {
            $query->where(['customer_id' => $id]);
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

        // grid filtering conditions
        $query->andFilterWhere([
            'customer_activity_id' => $this->customer_activity_id
        ]);

        return $dataProvider;
    }
}
