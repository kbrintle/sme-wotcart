<?php

namespace common\models\sales;

use Yii;
use common\models\sales\query\SalesOrderPaymentQuery;

/**
 * This is the model class for table "sales_order_payment".
 *
 * @property integer $id
 * @property integer $order_id
 * @property string $shipping_captured
 * @property string $amount_refunded
 * @property string $amount_canceled
 * @property string $shipping_amount
 * @property string $shipping_refunded
 * @property string $amount_paid
 * @property string $amount_authorized
 * @property string $amount_ordered
 * @property integer $quote_payment_id
 * @property string $method
 * @property string $protection_eligibility
 * @property string $cc_secure_verify
 * @property string $cc_exp_month
 * @property string $cc_approval
 * @property string $cc_last4
 * @property string $cc_status_description
 * @property string $cc_cid_status
 * @property string $cc_owner
 * @property string $cc_type
 * @property string $cc_exp_year
 * @property string $cc_status
 * @property string $cc_debug_response_body
 * @property string $cc_debug_request_body
 * @property string $cc_debug_response_serialized
 * @property string $cc_number_enc
 * @property string $cc_avs_status
 * @property string $cc_trans_id
 * @property string $account_status
 * @property string $paybox_request_number
 * @property string $address_status
 * @property string $additional_information
 * @property string $last_trans_id
 * @property integer $stripe_test
 * @property string $stripe_token
 */
class SalesOrderPayment extends \yii\db\ActiveRecord
{
    const CHECK_MONEY_ORDER = 'check_money_order';
    const CREDIT_CARD = 'credit_card';
    const STRIPE = 'stripe';
    const PAYPAL = 'paypal';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sales_order_payment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'quote_payment_id', 'stripe_test'], 'integer'],
            [['shipping_captured', 'amount_refunded', 'amount_canceled', 'shipping_amount', 'shipping_refunded', 'amount_paid', 'amount_authorized', 'amount_ordered'], 'number'],
            [['additional_information'], 'string'],
            [['method', 'protection_eligibility', 'cc_secure_verify', 'cc_exp_month', 'cc_approval', 'cc_last4', 'cc_status_description', 'cc_cid_status', 'cc_owner', 'cc_type', 'cc_exp_year', 'cc_status', 'cc_debug_response_body', 'cc_debug_request_body', 'cc_debug_response_serialized', 'cc_number_enc', 'cc_avs_status', 'cc_trans_id', 'account_status', 'paybox_request_number', 'address_status', 'last_trans_id', 'stripe_token'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'shipping_captured' => 'Shipping Captured',
            'amount_refunded' => 'Amount Refunded',
            'amount_canceled' => 'Amount Canceled',
            'shipping_amount' => 'Shipping Amount',
            'shipping_refunded' => 'Shipping Refunded',
            'amount_paid' => 'Amount Paid',
            'amount_authorized' => 'Amount Authorized',
            'amount_ordered' => 'Amount Ordered',
            'quote_payment_id' => 'Quote Payment ID',
            'method' => 'Method',
            'protection_eligibility' => 'Protection Eligibility',
            'cc_secure_verify' => 'Cc Secure Verify',
            'cc_exp_month' => 'Cc Exp Month',
            'cc_approval' => 'Cc Approval',
            'cc_last4' => 'Cc Last4',
            'cc_status_description' => 'Cc Status Description',
            'cc_cid_status' => 'Cc Cid Status',
            'cc_owner' => 'Cc Owner',
            'cc_type' => 'Cc Type',
            'cc_exp_year' => 'Cc Exp Year',
            'cc_status' => 'Cc Status',
            'cc_debug_response_body' => 'Cc Debug Response Body',
            'cc_debug_request_body' => 'Cc Debug Request Body',
            'cc_debug_response_serialized' => 'Cc Debug Response Serialized',
            'cc_number_enc' => 'Cc Number Enc',
            'cc_avs_status' => 'Cc Avs Status',
            'cc_trans_id' => 'Cc Trans ID',
            'account_status' => 'Account Status',
            'paybox_request_number' => 'Paybox Request Number',
            'address_status' => 'Address Status',
            'additional_information' => 'Additional Information',
            'last_trans_id' => 'Last Trans ID',
            'stripe_test' => 'Stripe Test',
            'stripe_token' => 'Stripe Token',
        ];
    }

    /**
     * @inheritdoc
     * @return SalesOrderPaymentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SalesOrderPaymentQuery(get_called_class());
    }
}