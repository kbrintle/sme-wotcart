<?php
    
    namespace console\controllers;
    
    use Yii;
    use common\models\catalog\CatalogStoreProduct;
    use common\models\core\Store;
    use yii\console\Controller;
    
    
    class SearchController extends Controller
    {
        
        public function actionIndex(){
            Yii::$app->response->format = yii\web\Response::FORMAT_HTML;
            // echo "hello";
            
        }
        
        /* public function actionFacet($store_id)
         {
         
         if (!$store_id) { //no company_id
         echo $this->ansiFormat("Company ID was not provided \n", Console::FG_RED);
         return false;
         }
         
         $store_products = CatalogStoreProduct::find()
         ->byStore($store_id)
         ->isActive()
         ->facetedSearch([
         'Comfort Level' => '5',
         'Mattress Size' => '10'
         ])->all();
         
         foreach($store_products as $product){
         echo "Found product_id #$product->id for store #$store_id \n";
         }
         
         }*/
        
        public function actionSearchaniseUpdate()
        {
            $data = ["schema" => [
            [
            [
            "name" => "title",
            "title" => "Product Name"
            ]
            ],
            [
            [
            "name" => "categories",
            "title" => "Product Categories",
            "type" => "text",
            "facet" => [
            "title" => "Categories",
            "type" => "select"
            ]
            ]
            ]
            ],
            ["items" => [
            "id" => "1",
            "title" => "Test product 1",
            "summary" => "Test description",
            "product_code" => "product1",
            "link" => "https://example.com/products/product1",
            "image_link" => "https://example.com/images/product1.jpg",
            "price" => "12",
            "categories" => "Category1",
            "status" => "active"
            ]
            ]
            ];
            
            //{"keys":{"api":"7T4H4D7O6d","private":"3R9m5X8B0K2v9S4r6o1e"}}
            $private = "3R9m5X8B0K2v9S4r6o1e";
            $url = "https://www.searchanise.com/api/items/update/json";
            $data_string = json_encode($data);
            $start = "?private_key=$private&full_import=start";
            $done = "?private_key=$private&full_import=done";
            
            $ch = curl_init($url . $start);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                        'Content-Type: application/json',
                        'Content-Length: ' . strlen($data_string)]
                        );
            $result = curl_exec($ch);
            //print_r($result);
        }
    }
