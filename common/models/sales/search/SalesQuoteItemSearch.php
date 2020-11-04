<?php

namespace common\models\sales\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\sales\SalesQuoteItem;


/**
 * SalesOrderSearch represents the model behind the search form about `common\models\sales\SalesOrder`.
 */
class SalesQuoteItemSearch extends SalesQuoteItem
{

    public $store_name;
    public $slug;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'product_id', 'store_id', 'parent_item_id', 'is_virtual', 'free_shipping', 'is_qty_decimal', 'coupon_code', 'qty_buy_x_get_y'], 'integer'],
            [['created_at', 'updated_at', 'slug'], 'safe'],
            [['description'], 'string'],
            [['weight', 'qty', 'price', 'discount_percent', 'discount_amount', 'tax_percent', 'tax_amount', 'row_total', 'row_total_with_discount', 'row_weight', 'tax_before_discount', 'original_custom_price', 'cost', 'price_incl_tax', 'row_total_incl_tax'], 'number'],
            [['quote_id', 'sku', 'name', 'product_type', 'redirect_url'], 'string', 'max' => 255],
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

    public function search($params, $id = null)
    {

        //var_dump($params);die;
        $query = SalesQuoteItem::find()->alias("i")->select("i.*, product.slug");

        if ($id) {
            $query->where(['quote_id' => $id]);
        }

        $query->joinWith(['product product'] );

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                 'attributes' => ['product.slug', 'sku', 'qty', 'price'],
            ]]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['LIKE', 'product.slug', $this->slug]);
        $query->andFilterWhere(['LIKE', 'i.sku', $this->sku]);
        $query->andFilterWhere(['LIKE', 'i.qty', $this->qty]);
        $query->andFilterWhere(['LIKE', 'i.price', $this->price]);


        //die($query->createCommand()->getRawSql());

        return $dataProvider;
    }
}
