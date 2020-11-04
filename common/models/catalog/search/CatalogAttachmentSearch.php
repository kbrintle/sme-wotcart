<?php

namespace common\models\catalog\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\catalog\CatalogAttachment;
use common\models\catalog\CatalogProductAttachment;


/**
 * CatalogProductSearch represents the model behind the search form about `common\models\catalog\CatalogProduct`.
 */
class CatalogAttachmentSearch extends CatalogAttachment
{
    public $catalogProductAttachment;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['attachment_id', 'store_id', 'created_at', 'updated_at', 'is_active', 'is_deleted'], 'integer'],
            [['title', 'file_name'], 'string'],
            [['catalogProductAttachment'], 'safe']
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
        $query = CatalogAttachment::find()->alias('attachment');

        $query->JoinWith(['catalogProductAttachment product_attachment']);

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
            'attachment.attachment_id' => $this->attachment_id,
            'attachment.store_id' => $this->store_id,
            'attachment.is_active' => $this->is_active,
            'attachment.is_deleted' => $this->is_deleted,
            'attachment.created_at' => $this->created_at,
            'attachment.modified_at' => $this->updated_at
        ])
            /*      ->andFilterWhere(['like', 'type', $this->type])
                  ->andFilterWhere(['like', 'brand.name', $this->productBrandName])
                  ->andFilterWhere(['like', 'sku.value', $this->productSku]);*/

            ->andFilterWhere(['like', 'attachment.title', $this->title])
            ->andFilterWhere(['like', 'attachment.file_name', $this->file_name])
            ->andFilterWhere(['like', 'product_attachment.product_id', $this->catalogProductAttachment]);

        return $dataProvider;
    }
}
