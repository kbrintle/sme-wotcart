<?php

namespace frontend\controllers;

use common\models\catalog\CatalogProductGallery;
use common\models\sales\SalesOrder;
use common\models\sales\SalesOrderItem;
use common\models\store\StoreEvent;
use Yii;
use yii\web\Controller;
use common\models\core\Store;
use common\models\catalog\CatalogProduct;
use common\models\catalog\CatalogCategory;
use frontend\components\Assets;
use common\models\catalog\CatalogStoreProduct;
use common\models\settings\SettingsStore;
use common\components\Notify;

class ApiController extends Controller
{

    public function actionSalesUpdate()
    {
        $items = SalesOrderItem::find()->all();
        foreach ($items as $item) {
            $pid = CatalogProduct::findBySku($item->sku);
            if (isset($pid->id)) {
                $item->product_id = $pid->id;
                $item->save(false);
            }
        }
    }

    public function actionSearchaniseUpdate($needs_only = false)
    {
        $processProducts = true;
        $processCategories = true;
        print "Start\n";
        $this->layout = '';
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 9000);

        $products = CatalogProduct::find()->where(['is_deleted' => false, 'is_active' => true])->andWhere(['IN', 'type', ['simple', 'grouped']]);
        if ($needs_only) {
            $processCategories = false;
            $products->andWhere(["needs_seachanise_update" => '1']);
        }
        $products = $products->all();
        $stores = Store::findAll(['is_deleted' => false, 'is_active' => true,]);
        $store_count = count($stores);
        $store_counter = 1;
        foreach ($stores as $store) {
            print "\n----------------------------------------------------------------------\n$store->name ($store_counter/$store_count)";
            $store_counter++;
            $items = [];
            $cats = [];

            if ($store->searchanise_private_key == null || $store->searchanise_api_key == null) {
                print "\n\n";
                print $store->id . "\n";
                print $store->searchanise_private_key . "\n\n";
                //Create api key
                if ($keys = json_decode(self::getSearchaniseApiKeys($store))) {
                    print "keys";
                    $store->searchanise_api_key = $keys->keys->api;
                    $store->searchanise_private_key = $keys->keys->private;
                    $store->save(false);
                }
            }

            if ($processProducts) {
                $i = 0;
                $j = 0;
                foreach ($products as $product) {
                    if (CatalogStoreProduct::findOne(["product_id" => $product->id, 'store_id' => $store->id])) {
                        $image = CatalogProductGallery::getImages($product->id);
                        if ($i >= 240) {
                            $j++;
                            $i = 0;
                        }
                        $items[$j][$i] = [
                            "id" => $product->id,
                            "title" => CatalogProduct::getName($product->id, $store->id),
                            "product_code" => CatalogProduct::getAttributeValue($product->id, 'sku', false, "0"),
                            "link" => '/' . $store->url . '/shop/products/' . $product->slug,
                            "image_link" => 'https://www.smeincusa.com/' . Assets::productResource($image),
                            "expired_skus" => (CatalogProduct::getAttributeValue($product->id, 'expired-skus', false, "0") . ", " . CatalogProduct::getAttributeValue($product->id, 'expired-skus', false, $store->id)),
                            "meta_keywords" => (CatalogProduct::getAttributeValue($product->id, 'meta-keywords', false, "0") . ", " . CatalogProduct::getAttributeValue($product->id, 'meta-keywords', false, $store->id)),
                            "status" => "active"
                        ];
                        $i++;
                    } else {
                        if ($needs_only) {
                            print "\n\nRemoving $product->slug from $store->name -";
                            $data_string = "private_key=$store->searchanise_private_key&id=$product->id";
                            $ch = curl_init("https://www.searchanise.com/api/items/delete/json");
                            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            $result = curl_exec($ch);
                            print $result;
                        }
                    }
                }

                $cats[] = null;

                foreach ($items as $item) {
                    $data = [
                        "schema" => [
                            [
                                "name" => "title",
                                "title" => "Product Name",
                                "text_search" => 'Y'
                            ],
                            [
                                "name" => "categories",
                                "title" => "Product Categories",
                                "type" => "text",
                                "facet" => [
                                    "title" => "Categories",
                                    "type" => "select",
                                ],
                            ],
                            [
                                "name" => "expired_skus",
                                "title" => "Expired Skus",
                                "type" => "text",
                                "text_search" => 'Y'
                            ],
                            [
                                "name" => "meta_keywords",
                                "title" => "Meta Keywords",
                                "type" => "text",
                                "text_search" => 'Y'
                            ]
                        ],
                        "items" => $item,
                        "categories" => $cats,
                        "pages" => []

                    ];
                    $data = rawurlencode(json_encode($data));
                    print "\n\n" . "Creating Products for $store->name - ";
                    $data_string = "private_key=$store->searchanise_private_key&data=$data";
                    $ch = curl_init("https://www.searchanise.com/api/items/update/json");
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $result = curl_exec($ch);
                    print $result;
                }
            }

            //Add Categories
            if ($processCategories) {
                $item = [];
                $categories = CatalogCategory::getAllCategories();
                foreach ($categories as $category) {
                    $cats[] = [
                        "id" => $category->id,
                        "parent_id" => $category->parent_id,
                        "title" => $category->name,
                        "summary" => $category->description,
                        "link" => '/' . $store->url . '/shop/category/' . $category->slug,
                    ];
                }

                $data = [
                    "schema" => [
                        [
                            "name" => "title",
                            "title" => "Product Name"
                        ],
                        [
                            "name" => "price",
                            "title" => "Product Price",
                            "facet" => [
                                "title" => "Price",
                                "type" => "range",
                                "ranges" => [[
                                    "title" => "from 0 to 150",
                                    "from" => "0",
                                    "to" => "150"
                                ],
                                    [
                                        "title" => "from 150 to 8000",
                                        "from" => "150",
                                        "to" => "8000"
                                    ]],
                            ],
                        ],
                        [
                            "name" => "categories",
                            "title" => "Product Categories",
                            "type" => "text",
                            "facet" => [
                                "title" => "Categories",
                                "type" => "select",
                            ],
                        ],
                    ],
                    "items" => $item,
                    "categories" => $cats,
                    "pages" => []

                ];

                print "\n" . "Creating Categories for $store->name - ";
                $data_string = "private_key=" . $store->searchanise_private_key . "&data=" . rawurlencode(json_encode($data));
                $ch = curl_init("https://www.searchanise.com/api/items/update/json");
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $result = curl_exec($ch);
                print $result;
            }
        }

        foreach ($products as $product) {
            $product->needs_seachanise_update = 0;
            $product->save(false);
        }
        die(print "\n\n\n\n\n  Searchanise Update Complete\n\n");
    }

    public function actionSearchaniseUpdateCategories()
    {
        $this->layout = '';
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 9000);

        $stores = Store::find()->where(['is_deleted' => false, 'is_active' => true])->all();

        foreach ($stores as $store) {
            $items = [];
            $cats = [];

            if ($store->searchanise_private_key == null || $store->searchanise_api_key == null) {

                //Create api key
                $keys = json_decode(self::getSearchaniseApiKeys($store));

                $store->searchanise_api_key = $keys->keys->api;
                $store->searchanise_private_key = $keys->keys->private;
                $store->save(false);
            }


            $products = CatalogProduct::find()->where([
                'is_deleted' => false,
                'is_active' => true
            ])
                ->andWhere(['IN', 'type', ['simple', 'grouped']])->all();

            $i = 0;
            $j = 0;
            foreach ($products as $product) {
                if ($i == 240) {
                    $j++;
                }
                $items[$j][] = [
                    "id" => $product->id,
                    "title" => CatalogProduct::getName($product->id),
                    "summary" => CatalogProduct::getAttributeValue($product->id, 'short-description'),
                    "product_code" => CatalogProduct::getAttributeValue($product->id, 'sku'),
                    "link" => '/' . $store->url . '/shop/products/' . $product->slug,
                    "image_link" => 'https//www.smeincusa.com/uploads/products' . CatalogProduct::getAttributeValue($product->id, 'base-image'),
                    "categories" => CatalogProduct::getCategoryString($product->id),
                    "status" => "active"
                ];

                $i++;
            }
            $cats[] = null;

            foreach ($items as $item) {
                $data = [
                    "schema" => [
                        [
                            "name" => "title",
                            "title" => "Product Name"
                        ],
                        [
                            "name" => "price",
                            "title" => "Product Price",
                            "facet" => [
                                "title" => "Price",
                                "type" => "range",
                                "ranges" => [[
                                    "title" => "from 0 to 150",
                                    "from" => "0",
                                    "to" => "150"
                                ],
                                    [
                                        "title" => "from 150 to 8000",
                                        "from" => "150",
                                        "to" => "8000"
                                    ]],
                            ],
                        ],
                        [
                            "name" => "categories",
                            "title" => "Product Categories",
                            "type" => "text",
                            "facet" => [
                                "title" => "Categories",
                                "type" => "select",
                            ],
                        ],
                    ],
                    "items" => $item,
                    "categories" => $cats,
                    "pages" => []

                ];

                print "\n\n" . "Creating Products for " . $store->name . "\n\n";

                $data_string = "private_key=" . $store->searchanise_private_key . "&data=" . rawurlencode(json_encode($data));
                $ch = curl_init("https://www.searchanise.com/api/items/update/json");
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $result = curl_exec($ch);
                print_r($result);
            }
        }
        print "\n\n\nSearchanise Update Categories Complete\n\n";
        die;
    }

    public function deactivatePastEvents()
    {
        $events = StoreEvent::find()->where(['is_active' => true])->all();
        foreach ($events as $event) {
            if (time() > strtotime($event->event_date)) {
                $event->is_active = false;
                $event->save(false);
            }
        }
    }

    public function actionSupervisorReminder()
    {
        $date_range_from = strtotime('-14 days');
        $date_range_to = strtotime('-1 days');
        $orders = SalesOrder::find()
            ->where(['status'=>10])
            ->andWhere(['between', 'created_at', $date_range_from, $date_range_to])
            ->all();

        foreach ($orders as $order){

            $settings = SettingsStore::find()->where(['store_id'=>$order->store_id])->one();
            $subject = 'SME - Orders Waiting Approval';

            $data = [
                'url' => Yii::$app->params['base_url'].'/admin/orders/view?id='.$order->order_id
            ];

            $supervisor_email = ($settings->supervisor_email) ? $settings->supervisor_email : 'kim@smeincusa.com';


            echo "Mailing". $supervisor_email . " for order " .$order->order_id ."<br>";
            $results = Notify::sendNotification([$supervisor_email =>'Superviso'], $subject , 'd-feefaf82b64542c1af9b88d9a62f492e', $data);

            var_dump($results);

        }
    }

    public function getSearchaniseApiKeys($store)
    {
        $data = [
            'url' => 'http://sme.wotcart.wideopentech.com/' . $store->url . "/",
            'email' => 'kbrintle@wideopentech.com',
            'parent_private_key' => '3R9m5X8B0K2v9S4r6o1e',
        ];

        $data_string = "parent_private_key=3R9m5X8B0K2v9S4r6o1e&url=" . $data['url'] . "&email=" . $data['email'];

        $ch = curl_init("https://www.searchanise.com/api/signup/json");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        return $result = curl_exec($ch);
    }
}