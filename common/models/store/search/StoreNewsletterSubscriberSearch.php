<?php

namespace common\models\store\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\store\StoreNewsletterSubscriber;

/**
 * NewsletterSubscriber represents the model behind the search form about `common\models\store\NewsletterSubscriber`.
 */
class StoreNewsletterSubscriberSearch extends StoreNewsletterSubscriber
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'store_id', 'customer_id', 'is_active', 'created_time'], 'integer'],
            [['change_status_at', 'email', 'confirm_code'], 'safe'],
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
        $query = StoreNewsletterSubscriber::find();

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
            'change_status_at' => $this->change_status_at,
            'customer_id' => $this->customer_id,
            'is_active' => $this->is_active,
            'created_time' => $this->created_time,
        ]);

        $query->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'confirm_code', $this->confirm_code]);

        return $dataProvider;
    }
}
