<?php

namespace common\models\core\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\core\CoreUrlRewrite;

/**
 * CoreUrlRewriteSearch represents the model behind the search form about `common\models\core\CoreUrlRewrite`.
 */
class CoreUrlRewriteSearch extends CoreUrlRewrite
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url_rewrite_id', 'store_id', 'category_id', 'product_id', 'is_system'], 'integer'],
            [['id_path', 'request_path', 'target_path', 'options', 'description'], 'safe'],
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
        $query = CoreUrlRewrite::find();

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
            'url_rewrite_id' => $this->url_rewrite_id,
            'store_id' => $this->store_id,
            'category_id' => $this->category_id,
            'product_id' => $this->product_id,
            'is_system' => $this->is_system,
        ]);

        $query->andFilterWhere(['like', 'id_path', $this->id_path])
            ->andFilterWhere(['like', 'request_path', $this->request_path])
            ->andFilterWhere(['like', 'target_path', $this->target_path])
            ->andFilterWhere(['like', 'options', $this->options])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}