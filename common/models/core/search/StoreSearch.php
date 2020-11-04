<?php

namespace common\models\core\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\core\Store;

/**
 * StoreSearch represents the model behind the search form about `common\models\Store`.
 */
class StoreSearch extends Store
{

    public $group;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'is_active', 'is_deleted', 'is_default', 'group_id'], 'integer'],
            [['group', 'name', 'url', 'legacy_store'], 'safe'],
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
        $query = Store::find()->alias('s');

        $query->joinWith(['group g' ] );

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
            's.id' => $this->id,
            's.created_at' => $this->created_at,
            's.updated_at' => $this->updated_at,
            's.is_active' => $this->is_active,
            's.is_deleted' => false,
            's.is_default' => $this->is_default,
            's.group_id'   => $this->group_id,
            'g.group' => $this->group,
        ]);

        $query->andFilterWhere(['like', 's.name', $this->name])
            ->andFilterWhere(['like', 's.url', $this->url])
            ->andFilterWhere(['like', 's.legacy_store', $this->legacy_store]);



        return $dataProvider;
    }

}