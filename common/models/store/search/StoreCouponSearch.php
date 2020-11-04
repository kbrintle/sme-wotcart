<?php

namespace common\models\store\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\store\StoreCoupon;

/**
 * StoreCouponSearch represents the model behind the search form about `common\models\store\StoreCoupon`.
 */
class StoreCouponSearch extends StoreCoupon
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'store_id', 'is_deleted', 'is_active', 'sort', 'created_at', 'modified_at'], 'integer'],
            [['image'], 'safe'],
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
        $query = StoreCoupon::find();

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
            'store_id' => $this->store_id,
            'is_deleted' => $this->is_deleted,
            'is_active' => $this->is_active,
            'sort' => $this->sort,
            'created_at' => $this->created_at,
            'modified_at' => $this->modified_at,
        ]);

        $query->andFilterWhere(['like', 'image', $this->image]);

        return $dataProvider;
    }
}
