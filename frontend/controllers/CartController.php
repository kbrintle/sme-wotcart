<?php

namespace frontend\controllers;

use backend\components\CurrentUser;
use common\models\promotion\PromotionFreeProduct;
use common\models\sales\SalesOrder;
use frontend\models\EmailQuoteForm;
use Yii;
use common\models\core\Store;
use app\components\StoreUrl;
use common\models\promotion\PromotionCode;
use yii\web\Controller;
use common\components\CurrentStore;
use common\models\catalog\CatalogProduct;
use common\models\catalog\CatalogProductOption;
use common\models\sales\SalesQuote;
use common\models\customer\CustomerReward;
use yii\helpers\ArrayHelper;
use common\models\promotion\PromotionStoreCode;
use yii\filters\AccessControl;

class CartController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'view', 'process', 'line-items', 'promocode'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'process', 'line-items', 'promocode'],
                        'roles' => ['@'],
                    ],
                ], 'denyCallback' => function ($rule, $action) {
                    return $this->redirect([StoreUrl::to('account/login')]);
                }
            ],
        ];
    }

    public function actionIndex()
    {
        $products = SalesQuote::getItems();
        $total = SalesQuote::getCurrentSaleQuote();
        $quoteForm = new EmailQuoteForm();

        $this->view->title = "Cart";

        return $this->render('index', [
            'quoteForm' => $quoteForm,
            'products' => $products,
            'total' => $total,
        ]);
    }

    public function actionView()
    {
        $this->view->title = "Cart";
        return $this->render('view');
    }

    public function actionProcess()
    {
        $request = Yii::$app->request;
        $action = $request->post('action');

        if ($request->isPost && $action) {
            $pid = $request->post('pid');

            if (strpos($pid, '-')) {
                $products = CatalogProduct::findAll([
                    'id' => explode('-', $pid),
                    'store_id' => [Store::NO_STORE, CurrentStore::getStoreId()],
                ]);
            } else {
                $product = ($pid ? CatalogProduct::findOne([
                    'id' => $pid,
                    'store_id' => [Store::NO_STORE, CurrentStore::getStoreId()],
                ]) : false);
            }


            if (isset($products) || isset($product)) {


                switch ($action) {
                    case 'addGrouped':
                        $items = $request->post('sku');
                        if (count($items) > 0) {
                            foreach ($items as $item) {
                                $item = ['product_id' => $item['pid'], 'sku' => $item['sku'], 'qty' => $item['qty'], 'price' => CatalogProduct::getPriceValue($item['pid'], false, $item['qty'], $item['sku'])];
                                SalesQuote::addOrUpdate($item);
                            }
                        }
                        break;

                    case 'add':
                        $sku = $request->post('sku');
                        $qty = ($request->post('qty')) ? $request->post('qty') : 1;
                        $item = ['product_id' => $pid, 'sku' => $sku, 'qty' => $qty, 'price' => CatalogProduct::getPriceValue($pid, false, $qty, $sku)];
                        SalesQuote::addOrUpdate($item);
                        break;

                    case 'sub':
                        $sku = $request->post('sku');
                        $qty = $request->post('qty');
                        $item = ['pid' => $pid, 'sku' => $sku, 'qty' => $qty, 'price' => CatalogProduct::getPriceValue($pid, false, $qty, $sku)];
                        SalesQuote::subOne($item);
                        break;

                    case 'remove':
                        $sku = $request->post('sku');
                        $qty = $request->post('qty');
                        $item = ['pid' => $pid, 'sku' => $sku, 'qty' => $qty, 'price' => CatalogProduct::getPriceValue($pid, false, $qty, $sku)];
                        SalesQuote::deleteItem($item);
                        break;
                    case 'refresh':
                        break;
                    case 'clear':
                        SalesQuote::deleteAllItems();
                        break;
                }

                $lineItemHTML = '';

                $quote = SalesQuote::getItems();
                if (isset($quote['items'])) {
                    $lineItemHTML .= '<form data-action="' . StoreUrl::to('cart/process') . '">';
                    $lineItemHTML .= '<div class="cart-product-list pad-xs">';
                    foreach ($quote['items'] as $quote_item) {
                        $lineItemHTML .= $this->renderPartial('../layouts/partials/_line_item', [
                            'quote_item' => $quote_item
                        ]);
                    }
                    $lineItemHTML .= '</div></form>';
                } else {
                    $lineItemHTML = '<p>Your cart is empty.</p>';
                }

                $orderSumHTML = "";

                if (isset($quote['items'])) {
                    if (($page = $request->post('page')) && $page !== false) {
                        switch ($page) {
                            case "cart":
                                $orderSumHTML = $this->renderPartial('../cart/partials/_order_summary', [
                                    "quote" => $quote
                                ]);
                                break;
                            case "checkout":
                                $orderSumHTML = $this->renderPartial('../checkout/partials/_order_summary', [
                                    "quote" => $quote
                                ]);
                                break;
                        }
                    }
                }

                $response['items'] = $quote;
                $response['orderSumHTML'] = $orderSumHTML;
                $response['lineItemHTML'] = $lineItemHTML;

                return json_encode($response);

            }
        }
        return false;
    }

    public function actionLineitems()
    {
        $html = '';

        $cart_items = SalesQuote::getItems();
        if (count($cart_items)) {
            $html .= '<form data-action="' . StoreUrl::to('cart/process') . '">';
            $html .= '<div class="cart-product-list pad-xs">';
            foreach ($cart_items as $cart_item) {
                $html .= $this->renderPartial('../layouts/partials/_line_item', [
                    'cart_item' => $cart_item
                ]);
            }
            $html .= '</div>';
            $html .= '</form>';
        } else {
            $html = '<p>Your cart is empty.</p>';
        }

        return $html;
    }

    public function actionPromocode()
    {
        $success = false;
        $discount = 'NONE';
        $request = Yii::$app->request;
        $code = strtoupper(filter_var($request->post('code'), FILTER_SANITIZE_STRING));

        if ($request->isPost && strlen($code) > 0 && $code !== 'CLEAR') {
            if ($discount = self::getPromoDiscount($code)) {
                self::setPromoCode($code);
                if (is_array($discount)) {
                    return json_encode(Yii::$app->controller->renderPartial('/layouts/partials/_free-product-modal', ['products' => $discount]));
                } else {
                    $success = true;
                    self::setPromoDiscount($discount);
                }
            }
        }

        if (is_array($request->post('code'))) {
            $code = $request->post('code');
            if (isset($code["GIFT_ID"])) {
                if ($catalog_product = CatalogProduct::findOne(['id' => $code["GIFT_ID"]])) {
                    $product_name = CatalogProduct::getName($catalog_product->id);
                    $product_sku = CatalogProduct::getSku($catalog_product->id);
                    if ($catalog_product->has_options) {
                        if ($options = CatalogProductOption::getOptions($catalog_product->id, CurrentStore::getStoreId())) {
                            return json_encode(Yii::$app->controller->renderPartial('/layouts/partials/_free-product-options-modal',
                                ['options' => $options,
                                    'product' => $catalog_product,
                                    'product_name' => $product_name,
                                    'product_sku' => $product_sku])); //return options view
                        }
                    } else {
                        $code = self::getPromoCode();
                        if ($products = self::getPromoDiscount($code)) {
                            foreach ($products as $product) {
                                if ($catalog_product->id === $product->id) {
                                    if ($price = CatalogProduct::getAttributeValue($product->id, 'price')) {
                                        $discount = number_format($price, 2);
                                        SalesQuote::addOrUpdate(['product_id' => $product->id, 'sku' => CatalogProduct::getSku($product->id), 'qty' => 1, 'price' => ['price' => $price]]);
                                        self::setPromoDiscount($discount);
                                        $success = true;
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if (isset($code["GIFT_SKU"])) {
                if (isset($code["GIFT_SKU"]["pid"]) && isset($code["GIFT_SKU"]["sku"])) {
                    $sku = $code["GIFT_SKU"]["sku"];
                    if ($catalog_product = CatalogProduct::findOne(['id' => $code["GIFT_SKU"]["pid"]])) {
                        $code = self::getPromoCode();
                        if ($products = self::getPromoDiscount($code)) {
                            foreach ($products as $product) {
                                if ($catalog_product->id === $product->id) {
                                    if ($price = CatalogProduct::getAttributeValue($product->id, 'price')) {
                                        $discount = number_format($price, 2);
                                        $item = ['product_id' => $product->id, 'sku' => $sku, 'qty' => 1, 'price' => ['price' => $price]];
                                        SalesQuote::addOrUpdate($item);
                                        self::setPromoDiscount($discount);
                                        $success = true;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        if (!$success) {
            self::setPromoCode('');
            self::setPromoDiscount(0);
            $discount = 'NONE';
        }

        return json_encode([
            'code' => $code,
            'discount' => $discount,
        ]);
    }

    public static function setPromoCode($code)
    {
        Yii::$app->session['discount_code'] = $code;
    }

    public static function getPromoCode()
    {
        $code = Yii::$app->session['discount_code'];
        return $code ? $code : false;
    }

    public static function setPromoDiscount($discount)
    {
        SalesQuote::setDiscount($discount);
        Yii::$app->session['discount'] = $discount;
    }

    public static function getPromoDiscount($code = false)
    {
        $code_set = $code ? true : false;
        $code = $code ? $code : self::getPromoCode();
        $current_store = CurrentStore::getStoreId();
        if ($code) {
            $promotion = PromotionCode::find()
                ->where(['code' => $code])
                ->andWhere('starts_at <= CURDATE()')
                ->andWhere(['OR', 'ends_at >= CURDATE()', 'ends_at IS NULL'])
                ->one();

            $active = PromotionStoreCode::findOne([
                'code_id' => $promotion->id,
                'store_id' => $current_store
            ]) ? true : false;

            if ($promotion && $active) {
                $pastOrders = SalesOrder::find()->where(['store_id' => $current_store, 'customer_id' => CurrentUser::getUserId()])->asArray()->all();
                switch ($promotion->event) {
                    case 'Once':
                        if ($pastOrders) {
                            $pastCodes = ArrayHelper::getColumn($pastOrders, 'coupon_code');
                            if (in_array(strtolower($code), $pastCodes) || in_array(strtoupper($code), $pastCodes)) {
                                return false;
                            }
                        }
                        break;
                    case 'First Checkout':
                        if ($pastOrders) {
                            return false;
                        }
                        break;
                }

                switch ($promotion->type) {
                    case 'Percentage':
                        $subTotal = SalesOrder::calculateSubtotal();
                        $recalculatedSubtotal = SalesOrder::calculateLowestPriceSubtotal();
                        $subTotal = ($subTotal > $recalculatedSubtotal) ? $recalculatedSubtotal : $subTotal;
                        return number_format(($promotion->amount / 100) * $subTotal, 2); //TODO CPM this needs to be changed
                        break;
                    case 'Fixed Amount':
                        return number_format($promotion->amount, 2);
                        break;
                    case 'Free Product(s)':
                        if ($quote = SalesQuote::getCurrentSaleQuote()) {
                            if (Yii::$app->request->isPost && $code_set) {
                                return CatalogProduct::find()
                                    ->alias("p")
                                    ->select("p.*")
                                    ->leftJoin('promotion_free_product cfp', 'cfp.product_id = p.id')
                                    ->where(["cfp.promotion_id" => $promotion->id])
                                    ->orderBy(['cfp.sort' => SORT_ASC])
                                    ->all();
                            } else {
                                return number_format(SalesQuote::getDiscount(), 2);
                            }
                        }
                        break;
                }
            }
        }

        return false;
    }

    public function actionRewardPoints()
    {
        $request = Yii::$app->request;
        $points = $request->post('points');

        if ($request->isPost && $points && $points !== 'CLEAR') {

            $points = ($points == (int)$points) ? (int)$points : (float)$points;
            if (is_float($points)) {
                return json_encode([
                    "code" => "ERROR",
                    'discount' => "NOT_INT"
                ]);
            }

            $usablePoints = CustomerReward::getUsablePoints(Yii::$app->user->id);
            if ($usablePoints < $points) {
                return json_encode([
                    "code" => "ERROR",
                    'discount' => "OVER",
                    'points' => $usablePoints
                ]);
            }

            $subTotal = SalesQuote::getCurrentSaleQuote()->subtotal;
            if (($points / 100) > (float)$subTotal) {
                return json_encode([
                    "code" => "ERROR",
                    'discount' => "CART_VALUE",
                    'points' => $usablePoints,
                    'salesQuote' => $subTotal
                ]);
            }

            SalesQuote::saveRewardPointsValue($points / 100);
            return json_encode([
                "code" => "SUCCESS",
                'salesQuote' => $subTotal
            ]);

        } else {
            SalesQuote::saveRewardPointsValue(0);
            return json_encode([
                "code" => "SUCCESS",
            ]);
        }
    }


    public function actionCreate($id)
    {
        $product = CatalogProduct::findOne($id);

        if ($product) {
            Yii::$app->cart->create($product);
            $this->redirect(['index']);
        }
    }

    public function actionDelete($id)
    {
        $product = CatalogProduct::findOne($id);
        if ($product) {
            Yii::$app->cart->delete($product);
            $this->redirect(['index']);
        }
    }

    public function actionUpdate($id, $quantity)
    {
        $product = CatalogProduct::findOne($id);
        if ($product) {
            Yii::$app->cart->update($product, $quantity);
            $this->redirect(['index']);
        }
    }

    public function actionCheckout()
    {
        Yii::$app->cart->checkOut(false);
        $this->redirect(['index']);
    }

    public function actionEmail()
    {

        $model = new EmailQuoteForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                echo true;
            } else {
                echo false;
            }

        } else {
            print_r($model->getErrors());
        }
    }

}