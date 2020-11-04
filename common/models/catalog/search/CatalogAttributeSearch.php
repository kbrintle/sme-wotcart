<?php

namespace common\models\catalog\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\catalog\CatalogAttribute;

/**
 * CatalogAttributeSearch represents the model behind the search form about `common\models\catalog\CatalogAttribute`.
 */
class CatalogAttributeSearch extends CatalogAttribute
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type_id', 'category_id', 'is_filterable', 'created_at', 'modified_at', 'is_active', 'is_deleted', 'is_default'], 'integer'],
            [['label'], 'safe'],
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
        $query = CatalogAttribute::find();

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
            'type_id' => $this->type_id,
            'category_id' => $this->category_id,
            'is_filterable' => $this->is_filterable,
            'created_at' => $this->created_at,
            'modified_at' => $this->modified_at,
            'is_active' => $this->is_active,
            'is_deleted' => $this->is_deleted,
            'is_default' => $this->is_default,
        ]);

        $query->andFilterWhere(['like', 'label', $this->label]);

        return $dataProvider;
    }
}
