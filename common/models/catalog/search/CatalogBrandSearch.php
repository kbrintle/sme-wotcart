<?php

namespace common\models\catalog\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\catalog\CatalogBrand;

/**
 * CatalogBrandSearch represents the model behind the search form about `common\models\catalog\CatalogBrand`.
 */
class CatalogBrandSearch extends CatalogBrand
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'store_id', 'created_at', 'modified_at', 'is_active', 'is_deleted'], 'integer'],
            [['name', 'slug', 'text'], 'safe'],
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
        $query = CatalogBrand::find();

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
            'created_at' => $this->created_at,
            'modified_at' => $this->modified_at,
            'is_active' => $this->is_active,
            'is_deleted' => $this->is_deleted,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'text', $this->text]);

        return $dataProvider;
    }
}
