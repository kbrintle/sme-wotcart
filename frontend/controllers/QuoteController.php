<?php

namespace frontend\controllers;

use common\components\CurrentStore;
use common\models\catalog\CatalogAttribute;
use common\models\catalog\CatalogProduct;
use Yii;
use yii\web\Controller;
use common\models\catalog\CatalogAttributeValue;
use common\models\settings\SettingsStore;
use common\components\Notify;

class QuoteController extends Controller
{

    public function actionModal()
    {
        if ($post = Yii::$app->request->post()) {
            $catalog_attribute = CatalogAttributeValue::findOne(['attribute_id' => CatalogAttribute::findOne(['slug' => 'sku'])->id, 'store_id' => CurrentStore::getStore(), 'value' => $post['sku']]);
            if ($catalog_attribute) {
                return Yii::$app->controller->renderPartial('/products/partials/_get-quote-modal', ['id' => $catalog_attribute->product_id, 'sku' => $post['sku']]);
            }
        }
        return false;
    }

    public function actionSubmit()
    {
        if ($post = Yii::$app->request->post()) {
            $settingsStore = SettingsStore::getSettings();

            if (isset($settingsStore->sales_email)) {
                $to[] = ["email" => 'quote@smeincusa.com'];
            }

            if (isset($to) && !empty($to[0])) {

                $dynamicData = [
                    "name" => $post['GetQuoteForm']['name'],
                    "clinic" => $post['GetQuoteForm']['clinic'],
                    "email" => $post['GetQuoteForm']['email'],
                    "phone" => $post['GetQuoteForm']['phone'],
                    "comment" => $post['GetQuoteForm']['notes'],
                ];

                $groupProductJson = json_decode($post['GetQuoteForm']['product']);
                if (is_array($groupProductJson)) {
                    $dynamicData["product"] = "";

                    foreach ($groupProductJson as $i => $product) {
                        if (!isset($product->sku)) {
                            $product->sku = CatalogProduct::getSku($product->pid);
                        }
                        $dynamicData["product"] .= "product: " . CatalogProduct::getName($product->pid) . ", sku: " . $product->sku . " , qty: " . $product->qty;
                        $dynamicData["product"] .= (sizeof($groupProductJson) === ($i + 1)) ? "" : " | ";
                    }
                } else {
                    $dynamicData["product"] = CatalogProduct::getName($post['GetQuoteForm']['product']);
                    $dynamicData["sku"] = CatalogProduct::getSku($post['GetQuoteForm']['product']);
                }
                $data = [
                    "personalizations" => [[
                        'to' => $to,
                        'dynamic_template_data' => $dynamicData,
                    ]],
                    "from" => [
                        "email" => Yii::$app->params['from_email']['address'],
                        "name" => Yii::$app->params['from_email']['name']
                    ],

                    "template_id" => "d-bd242b8604424bcfba53da39f04548f1"
                ];

                if (Notify::sendJsonMail($data)) {
                    return true;
                }
            }
        }


    }
}