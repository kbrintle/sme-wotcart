<?php

namespace common\models\cms\search;

use common\components\CurrentStore;
use common\models\cms\CmsPage;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CostumerSearch represents the model behind the search form about `common\models\Customer`.
 */
class CmsPageSearch extends CmsPage
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'author_id' ], 'integer'],
            [[ 'title', 'created_time', 'modified_time', 'template', 'url_key', 'content', 'meta_description', 'meta_keywords'], 'safe'],
            [['is_active'], 'boolean']
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
        $query = CmsPage::find();

        // add conditions that should always apply here
        $store_id = (CurrentStore::getStoreId()) ? CurrentStore::getStoreId() : 0;
        $query->joinWith('store st');
        $query->andWhere([
            'cms_page_store.store_id'     => $store_id
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

//        // grid filtering conditions
//        $query->andFilterWhere([
//            'id' => $this->id,
//            'local_store' => $this->local_store,
//            'created_at' => $this->created_at,
//            'updated_at' => $this->updated_at,
//            'is_active' => $this->is_active,
//            'is_deleted' => $this->is_deleted,
//            'last_login' => $this->last_login,
//        ]);
//
//        $query->andFilterWhere(['like', 'email', $this->email])
//            ->andFilterWhere(['like', 'password', $this->password])
//            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
//            ->andFilterWhere(['like', 'access_token', $this->access_token])
//            ->andFilterWhere(['like', 'first_name', $this->first_name])
//            ->andFilterWhere(['like', 'last_name', $this->last_name])
//            ->andFilterWhere(['like', 'time_zone', $this->time_zone]);

        return $dataProvider;
    }
}
