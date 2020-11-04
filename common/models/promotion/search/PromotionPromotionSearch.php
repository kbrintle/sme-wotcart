<?php

namespace common\models\promotion\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\promotion\PromotionPromotion;

/**
 * PromotionPromotionSearch represents the model behind the search form about `common\models\promotion\PromotionPromotion`.
 */
class PromotionPromotionSearch extends PromotionPromotion
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'store_id', 'starts_at', 'ends_at', 'created_at', 'modified_at', 'is_active', 'is_deleted'], 'integer']
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
        $query = PromotionPromotion::find()->select('promotion.*, store.name')->alias('promotion');

        $query->JoinWith(['store store']);

        // add conditions that should always apply here
        $query->where(['<>','promotion.store_id', "NULL"]);
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
            'store_id' => $this->store_id,
            'starts_at' => $this->starts_at,
            'ends_at' => $this->ends_at,
            'created_at' => $this->created_at,
            'modified_at' => $this->modified_at,
            'is_active' => $this->is_active,
            'is_deleted' => $this->is_deleted,
        ]);

        return $dataProvider;
    }
}
