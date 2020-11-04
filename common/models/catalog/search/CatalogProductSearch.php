<?php

namespace common\models\catalog\search;

use common\components\CurrentStore;
use common\models\catalog\CatalogStoreProduct;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\catalog\CatalogProduct;
use yii\helpers\ArrayHelper;

/**
 * CatalogProductSearch represents the model behind the search form about `common\models\catalog\CatalogProduct`.
 */
class CatalogProductSearch extends CatalogProduct
{
    public $catalogCategoryProducts;

    public $productSetName;
    public $productBrand;
    public $productSku;
    public $productName;
    public $productPrice;
    public $productSpecialPrice;
    public $productActive;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parent_id', 'store_id', 'brand_id', 'feature_id', 'created_at', 'modified_at', 'is_active', 'is_deleted'], 'integer'],
            [['slug'], 'string'],
            [['type', 'catalogCategoryProducts', 'productSetName', 'productBrand', 'productSku', 'productName', 'productPrice', 'productSpecialPrice', 'productActive'], 'safe']
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
        $store_ids = CatalogStoreProduct::find()->where(['store_id' => CurrentStore::getStoreId()])->all();
        $store_ids = ArrayHelper::getColumn($store_ids, 'product_id');

        $query = CatalogProduct::find()->alias('product');
        $query->JoinWith(['productBrand brand']);
        $query->JoinWith(['productSku sku']);
        $query->JoinWith(['productPrice price']);
        $query->JoinWith(['productSpecialPrice special_price']);
        $query->JoinWith(['productName product_name']);

        if (!empty($store_ids))
            $query->andWhere(['product.id' => $store_ids]);

        $query->groupBy(['product.id']);

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
            'product.id' => $this->id,
            'product.parent_id' => $this->parent_id,
            'product.store_id' => $this->store_id,
            'product.brand_id' => $this->brand_id,
            'product.feature_id' => $this->feature_id,
            'product.created_at' => $this->created_at,
            'product.modified_at' => $this->modified_at,
            'product.is_active' => $this->is_active,
            'product.is_deleted' => $this->is_deleted,
            'type' => $this->type]);
        $query->andFilterWhere(['like', 'product.slug', $this->slug]);
        $query->andFilterWhere(['like', 'brand.name', $this->productBrand]);
        $query->andFilterWhere(['like', 'sku.value', $this->productSku]);
        $query->andFilterWhere(['like', 'price.value', $this->productPrice]);
        $query->andFilterWhere(['like', 'special_price.value', $this->productSpecialPrice]);
        $query->andFilterWhere(['like', 'product_name.value', $this->productName]);

        $dataProvider->pagination->pageSize = 50;

        return $dataProvider;
    }
}
