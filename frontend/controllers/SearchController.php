<?php

namespace frontend\controllers;

use frontend\models\GlobalSearchForm;
use Yii;
use yii\web\Controller;
use common\components\CurrentStore;

class SearchController extends Controller
{
    public function actionIndex()
    {
        $model = new GlobalSearchForm();
        $product_ids = [];
        $store = CurrentStore::getStore();
        if (isset($_GET['q'])) {
            $model->keyword = $_GET['q'];
            $ch = curl_init("http://searchanise.com/search?api_key=" . urlencode($store->searchanise_api_key) . "&q=" . urlencode($_GET['q']) . "&maxResults=40");
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            if (isset($result)) {
                $result = json_decode($result);
                if (!empty($result->items)) {
                    foreach ($result->items as $item => $itemObject) {
                        $product_ids[] = $itemObject->product_id;
                    }
                } else {
                    $model->keyword = $_GET['q'];
                    $product_ids = $model->getSearchResults();
                }
            }
        }
        return $this->render('index', [
            'model' => $model,
            'product_ids' => $product_ids
        ]);
    }

}