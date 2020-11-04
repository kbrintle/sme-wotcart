<?php

namespace common\models\promotion\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\promotion\PromotionCode;

/**
 * PromotionCodeSearch represents the model behind the search form about `common\models\promotion\PromotionCode`.
 */
class PromotionCodeSearch extends PromotionCode
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'starts_at', 'ends_at', 'created_at', 'modified_at', 'is_active', 'is_deleted'], 'integer'],
            [['code', 'type', 'amount'], 'safe'],
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
        $query = PromotionCode::find();

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
            'starts_at' => $this->starts_at,
            'ends_at' => $this->ends_at,
            'created_at' => $this->created_at,
            'modified_at' => $this->modified_at,
            'is_active' => $this->is_active,
            'is_deleted' => $this->is_deleted,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'amount', $this->amount]);

        return $dataProvider;
    }
}
