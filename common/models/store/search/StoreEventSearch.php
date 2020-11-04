<?php

namespace common\models\store\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\store\StoreEvent;
use common\components\CurrentStore;

/**
 * StoreEventSearch represents the model behind the search form of `common\models\store\StoreEvent`.
 */
class StoreEventSearch extends StoreEvent
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'store_id', 'author_id', 'is_active', 'is_deleted', 'created_at', 'modified_at'], 'integer'],
            [['title', 'event_date', 'content'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = StoreEvent::find()->where(['store_id' => CurrentStore::getStoreId()]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => [ 'is_active'=>SORT_DESC, 'event_start_date'=>SORT_ASC]]
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
            'event_date' => $this->event_date,
            'store_id' => $this->store_id,
            'author_id' => $this->author_id,
            'is_active' => $this->is_active,
            'is_deleted' => $this->is_deleted,
            'created_at' => $this->created_at,
            'modified_at' => $this->modified_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
              ->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }
}