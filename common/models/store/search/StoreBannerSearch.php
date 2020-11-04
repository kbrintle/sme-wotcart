<?php

namespace common\models\store\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\store\StoreBanner;

/**
 * StoreBannerSearch represents the model behind the search form about `common\models\store\StoreBanner`.
 */
class StoreBannerSearch extends StoreBanner
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'store_id', 'is_deleted', 'is_active', 'created_at', 'modified_at'], 'integer'],
            [['page_location', 'button_url', 'button_text', 'content', 'title'], 'string', 'max' => 255],
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
        $query = StoreBanner::find();

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
            'page_location' => $this->page_location,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'modified_at' => $this->modified_at,
        ]);

        $query->andFilterWhere(['like', 'image', $this->image]);

        return $dataProvider;
    }
}
