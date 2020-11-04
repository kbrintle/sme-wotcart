<?php

namespace common\models\catalog\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\catalog\CatalogProductGallery;

/**
 * CatalogProductSearch represents the model behind the search form about `common\models\catalog\CatalogProduct`.
 */
class CatalogProductGallerySearch extends CatalogProductGallery
{

    //public CatalogProductGalleryImages;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['value'], 'string'],
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
    public function search($params,$id)
    {
        $query = CatalogProductGallery::find()->Where(['product_id'=>$id])->orderBy(['sort'=>SORT_ASC]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query
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
            //   'created_at' => $this->created_at,
            //  'modified_at' => $this->modified_at,
        ]);
        //  ->andFilterWhere(['like', 'file_name', $this->file_name]);

        return $dataProvider;
    }


}