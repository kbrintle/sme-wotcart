<?php

namespace common\models\customer\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\customer\Lead;

/**
 * LeadSearch represents the model behind the search form about `common\models\customer\Lead`.
 */
class LeadSearch extends Lead
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'is_deleted', 'created_at'], 'integer'],
            [['practitioner_name', 'clinic_name', 'clinic_position', 'ordering_contact_name', 'clinic_address', 'clinic_city', 'clinic_state', 'clinic_zip', 'clinic_phone', 'clinic_fax', 'clinic_email', 'contact_email', 'website', 'network_member_list', 'how_hear'], 'safe'],
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
        $query = Lead::find();

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
            'is_deleted' => $this->is_deleted,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'practitioner_name', $this->practitioner_name])
            ->andFilterWhere(['like', 'clinic_name', $this->clinic_name])
            ->andFilterWhere(['like', 'clinic_position', $this->clinic_position])
            ->andFilterWhere(['like', 'ordering_contact_name', $this->ordering_contact_name])
            ->andFilterWhere(['like', 'clinic_address', $this->clinic_address])
            ->andFilterWhere(['like', 'clinic_city', $this->clinic_city])
            ->andFilterWhere(['like', 'clinic_state', $this->clinic_state])
            ->andFilterWhere(['like', 'clinic_zip', $this->clinic_zip])
            ->andFilterWhere(['like', 'clinic_phone', $this->clinic_phone])
            ->andFilterWhere(['like', 'clinic_fax', $this->clinic_fax])
            ->andFilterWhere(['like', 'clinic_email', $this->clinic_email])
            ->andFilterWhere(['like', 'contact_email', $this->contact_email])
            ->andFilterWhere(['like', 'website', $this->website])
            ->andFilterWhere(['like', 'how_hear', $this->how_hear]);

        return $dataProvider;
    }
}