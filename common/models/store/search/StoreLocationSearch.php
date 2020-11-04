<?php

namespace common\models\store\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\store\StoreLocation;

/**
 * LocationSearch represents the model behind the search form about `common\models\Location`.
 */
class StoreLocationSearch extends StoreLocation
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'store_id', 'state_id', 'is_active', 'sort', 'zoom_level'], 'integer'],
            [['name', 'slug', 'type', 'address', 'alt_address', 'city', 'country', 'zipcode', 'state', 'email', 'phone', 'fax', 'description', 'hours', 'link', 'latitude', 'longtitude', 'image_icon'], 'safe'],
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
        $query = StoreLocation::find();

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
            'state_id' => $this->state_id,
            'is_active' => $this->is_active,
            'sort' => $this->sort,
            'zoom_level' => $this->zoom_level,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'alt_address', $this->alt_address])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'zipcode', $this->zipcode])
            ->andFilterWhere(['like', 'state', $this->state])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'fax', $this->fax])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'hours', $this->hours])
            ->andFilterWhere(['like', 'link', $this->link])
            ->andFilterWhere(['like', 'latitude', $this->latitude])
            ->andFilterWhere(['like', 'longtitude', $this->longtitude])
            ->andFilterWhere(['like', 'image_icon', $this->image_icon]);

        return $dataProvider;
    }
}