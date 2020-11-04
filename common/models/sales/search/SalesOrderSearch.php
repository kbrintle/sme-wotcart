<?php

namespace common\models\sales\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\sales\SalesOrder;


/**
 * SalesOrderSearch represents the model behind the search form about `common\models\sales\SalesOrder`.
 */
class SalesOrderSearch extends SalesOrder
{

    public $store_name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'is_virtual', 'store_id', 'email_sent', 'quote_id', 'quote_address_id', 'billing_address_id', 'shipping_address_id', 'can_ship_partially_item', 'can_ship_partially', 'customer_group_id', 'customer_is_guest', 'customer_id', 'customer_note_notify', 'updated_at', 'total_item_count', 'paypal_ipn_customer_notified'], 'integer'],
            [['order_id', 'status', 'shipping_description', 'coupon_code', 'discount_description', 'shipping_method', 'customer_email', 'customer_firstname', 'customer_lastname', 'customer_middlename', 'customer_prefix', 'customer_suffix', 'customer_taxvat', 'hold_before_state', 'hold_before_status', 'order_currency_code', 'remote_ip', 'store_name', 'customer_note', 'created_at'], 'safe'],
            [['discount_amount', 'discount_canceled', 'discount_invoiced', 'discount_refunded', 'grand_total', 'shipping_amount', 'shipping_canceled', 'shipping_invoiced', 'subtotal', 'subtotal_canceled', 'subtotal_invoiced', 'subtotal_refunded', 'tax_amount', 'tax_canceled', 'tax_invoiced', 'tax_refunded', 'total_canceled', 'total_invoiced', 'total_offline_refunded', 'total_online_refunded', 'total_paid', 'total_qty_ordered', 'total_refunded', 'adjustment_negative', 'adjustment_positive', 'payment_authorization_amount', 'shipping_tax_refunded', 'shipping_refunded', 'shipping_tax_amount', 'shipping_discount_amount', 'shipping_incl_tax', 'subtotal_incl_tax', 'total_due', 'weight'], 'number'],
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
        $query = SalesOrder::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['order_id', 'store_id', 'customer_firstname', 'customer_lastname', 'created_at', 'grand_total', 'status'],
                'defaultOrder' => ['created_at' => SORT_DESC]
        ]]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'order_id', $this->order_id])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['=', 'store_id', $this->store_id])
            ->andFilterWhere(['like', 'shipping_description', $this->shipping_description])
            ->andFilterWhere(['like', 'coupon_code', $this->coupon_code])
            ->andFilterWhere(['like', 'discount_description', $this->discount_description])
            ->andFilterWhere(['like', 'shipping_method', $this->shipping_method])
            ->andFilterWhere(['like', 'customer_email', $this->customer_email])
            ->andFilterWhere(['like', 'customer_firstname', $this->customer_firstname])
            ->andFilterWhere(['like', 'customer_lastname', $this->customer_lastname])
            ->andFilterWhere(['like', 'customer_middlename', $this->customer_middlename])
            ->andFilterWhere(['like', 'customer_prefix', $this->customer_prefix])
            ->andFilterWhere(['like', 'customer_suffix', $this->customer_suffix])
            ->andFilterWhere(['like', 'customer_taxvat', $this->customer_taxvat])
            ->andFilterWhere(['like', 'hold_before_state', $this->hold_before_state])
            ->andFilterWhere(['like', 'hold_before_status', $this->hold_before_status])
            ->andFilterWhere(['like', 'order_currency_code', $this->order_currency_code])
            ->andFilterWhere(['like', 'remote_ip', $this->remote_ip])
            ->andFilterWhere(['like', 'customer_note', $this->customer_note])
            ->andFilterWhere(['like', 'grand_total', $this->grand_total]);

        if (isset($this->created_at) && $this->created_at != '') {
            $date_explode = explode(" - ", $this->created_at);
            $date1 = strtotime(trim($date_explode[0]));
            $date2 = strtotime(trim($date_explode[1]));
            $query->andFilterWhere(['between', 'created_at', $date1, $date2]);
        }

        //echo $query->createCommand()->getRawSql();die;

        return $dataProvider;
    }
}
