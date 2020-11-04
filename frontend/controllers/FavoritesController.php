<?php

namespace frontend\controllers;

use app\components\StoreUrl;
use common\components\CurrentStore;
use common\models\catalog\CatalogAttribute;
use common\models\catalog\CatalogProduct;
use common\models\store\StoreFavoriteList;
use common\models\store\StoreFavoriteListItem;
use frontend\components\CurrentCustomer;
use Yii;
use yii\web\Controller;
use common\models\sales\SalesQuote;
use common\models\catalog\CatalogAttributeValue;
use yii\web\HttpException;

class FavoritesController extends Controller
{


    public function actionList()
    {

        if (Yii::$app->user->isGuest) {
            return $this->redirect('/sme/account/login');
        }

        $favorite_list = StoreFavoriteList::find()->where([
            'customer_id' => CurrentCustomer::getCustomerId(),
            'is_deleted' => false
        ])->all();

        return $this->render('list', [
            'lists' => $favorite_list,
        ]);
    }

    public function actionCreateList()
    {
        if ($data = Yii::$app->request->post()) {
            $list = new StoreFavoriteList();
            $list->title = $data['title'];
            $list->customer_id = CurrentCustomer::getCustomerId();
            $list->created_at = time();

            if ($list->save(false)) {
                Yii::$app->session->setFlash('success', "Your favorite list has been created.");
                return $this->redirect([StoreUrl::to('favorites/list')]);
            } else {
                Yii::$app->session->setFlash('error', "An error occurred while creating your favorite list.");
                return $this->redirect([StoreUrl::to('favorites/list')]);
            }
        }
        return $this->redirect([StoreUrl::to('favorites/list')]);
    }

    public function actionListEdit()
    {

    }

    public function actionAdd()
    {
        if (Yii::$app->request->isAjax) {
            if (!$post = Yii::$app->request->post()) {
                return false;
            }
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $response = [
                'status' => 1,
            ];
            if (is_array($post['skus'])) {
                foreach ($post['skus'] as $product) {

                    $favorite_item = StoreFavoriteListItem::find()->where([
                        'list_id' => $post['folder'],
                        'product_id' => $product['pid'],
                        'sku' => $product['sku']
                    ])->one();

                    if (isset($favorite_item) && !empty($favorite_item)) {
                        $qty = ($product['qty']) ? $product['qty'] : 1;
                        $favorite_item->qty = $favorite_item->qty + $qty;
                    } else {
                        $last_favorite_item = StoreFavoriteListItem::find()->where([
                            'list_id' => $post['folder'],
                            'product_id' => $product['pid']
                        ])->max("sort");

                        if (isset($last_favorite_item) && !empty($last_favorite_item)) {
                            $sort = $last_favorite_item;
                        } else {
                            $sort = 1;
                        }

                        $favorite_item = new StoreFavoriteListItem();
                        $favorite_item->list_id = $post['folder'];
                        $favorite_item->sku = $product['sku'];
                        $favorite_item->product_id = $product['pid'];
                        $favorite_item->qty = isset($product['qty']) ? $product['qty'] : 1;
                        $favorite_item->sort = $sort;
                    }

                    if ($favorite_item->save()) {
                        $response = [
                            'status' => 1,
                        ];
                    } else {
                        $response = [
                            'status' => 0,
                            'error' => $favorite_item->errors,
                        ];
                        return $response;
                    }
                }
                return $response;
            }
        }
    }

    public function actionUpdate($list_id = null)
    {
        if ($list_id == null) {
            return $this->redirect([StoreUrl::to('favorites/list')]);
        }

        $favorite_list = StoreFavoriteList::find()->where([
            'customer_id' => CurrentCustomer::getCustomerId(),
            'list_id' => $list_id,
            'is_deleted' => false
        ])->one();

        if (!$favorite_list) {
            return $this->redirect([StoreUrl::to('favorites/list')]);
        }

        $favorite_items = StoreFavoriteList::getFavoriteItems($list_id);

        return $this->render('update', [
            'list' => $favorite_list,
            'items' => $favorite_items,
            'list_id' => $list_id,
        ]);
    }

    public function actionRemoveList($id)
    {
        $favorite_list = StoreFavoriteList::find()->where([
            'customer_id' => CurrentCustomer::getCustomerId(),
            'list_id' => $id,
        ])->one();

        if ($favorite_list) {
            $favorite_list->is_deleted = true;
            if ($favorite_list->save()) {
                Yii::$app->session->setFlash('success', "The list has been successfully removed.");
                return $this->redirect([StoreUrl::to('favorites/list')]);
            } else {
                Yii::$app->session->setFlash('error', "There was an error trying to remove the list.");
                return $this->redirect([StoreUrl::to('favorites/list')]);
            }
        } else {
            Yii::$app->session->setFlash('error', "There was an error trying to remove the list.");
            return $this->redirect([StoreUrl::to('favorites/list')]);
        }
    }

    public function actionRemoveItem($id)
    {
        $favorite_item = StoreFavoriteListItem::find()->where([
            'item_id' => $id,
        ])->one();

        if ($favorite_item) {
            $list_id = $favorite_item->list_id;
            $favorite_list = StoreFavoriteList::find()->where([
                'customer_id' => CurrentCustomer::getCustomerId(),
                'list_id' => $list_id,
            ])->one();

            if ($favorite_list) {
                if ($favorite_item->delete()) {
                    Yii::$app->session->setFlash('success', "The item has been successfully removed.");
                    return $this->redirect([StoreUrl::to('favorites/update/' . $list_id)]);
                } else {
                    Yii::$app->session->setFlash('error', "There was an error trying to remove the item.");
                    return $this->redirect([StoreUrl::to('favorites/update/' . $list_id)]);
                }
            }
        } else {
            Yii::$app->session->setFlash('error', "There was an error trying to remove the item.");
            return $this->redirect([StoreUrl::to('favorites/list')]);
        }
    }

    public function actionFixFavoriteIds()
    {
        $items = StoreFavoriteListItem::find()->all();
        foreach ($items as $item) {
            $product = CatalogProduct::findBySku($item->sku);
            if ($product) {
                $item->product_id = $product->id;
                $item->save(false);
            }

        }

    }

    public function actionAction($id = false)
    {
        if (Yii::$app->request->post() && $id) {
            $post = Yii::$app->request->post();
            if (!$favoriteList = StoreFavoriteList::find()->where([
                'customer_id' => CurrentCustomer::getCustomerId(),
                'list_id' => $id,
                'is_deleted' => false
            ])->one()) {
                throw new HttpException(404, 'The requested Item could not be found.');
            }

            if (isset($post['move_to'])) {
                if (isset($post['cb']) && !empty($post['cb'])) {
                    foreach ($post['cb'] as $sku => $trueFalse) {
                        if ($trueFalse == true) {
                            if ($favorite_item = StoreFavoriteListItem::find()->where(['list_id' => $id, 'sku' => $sku,])->one()) {
                                if ($favorite_item_in_second_list = StoreFavoriteListItem::find()->where(['list_id' => $post['move_to'], 'sku' => $sku,])->one()) {
                                    $favorite_item_in_second_list->qty += (int)$post['qty'][$sku];
                                    if ($favorite_item_in_second_list->save()) {
                                        $favorite_item->delete();
                                    }
                                } else {
                                    $favorite_item->list_id = $post['move_to'];
                                    if (!$favorite_item->save()) {
                                        return "Server Error";
                                    }
                                }

                            }
                        }
                    }
                } else {
                    return "Check the box next to the favorite item you wish to move.";
                }
                return true;
            };

            if ($post['action'] === "name-change") {
                $favoriteList->title = $post['list-name'];
                if (!$favoriteList->save(false)) {
                    Yii::$app->session->setFlash('error', "An error occurred while updating your favorites.");
                }
                Yii::$app->session->setFlash('success', "Your favorite items have been updated.");
            }

            if ($post['action'] === "update") {
                $sort = 1;
                foreach ($post['qty'] as $sku => $qty) {
                    $favorite_item = StoreFavoriteListItem::find()->where([
                        'sku' => $sku,
                        'list_id' => $id
                    ])->one();

                    if ($favorite_item) {
                        $favorite_item->qty = intval($qty);
                        $favorite_item->sort = $sort;
                        if ($favorite_item->save()) {
                            Yii::$app->session->setFlash('success', "Your favorite items have been updated.");
                        } else {
                            Yii::$app->session->setFlash('error', "An error occurred while updating your favorites.");
                        }
                    } else {
                        Yii::$app->session->setFlash('error', "An error occurred while updating your favorites.");
                    }
                    $sort++;
                }
            }

            if ($post['action'] === "addtocart") {
                if (isset($post['cb']) && !empty($post['cb'])) {
                    foreach ($post['cb'] as $sku => $trueFalse) {
                        if ($trueFalse == true) {
                            $favorite_item = StoreFavoriteListItem::find()->where([
                                'list_id' => $id,
                                'sku' => $sku,
                            ])->one();
                            $item = ['product_id' => $favorite_item->product_id, 'sku' => $favorite_item->sku, 'qty' => $favorite_item->qty, 'price' => CatalogProduct::getPriceValue($favorite_item->product_id, false, $favorite_item->qty, $favorite_item->sku)];
                            SalesQuote::addOrUpdate($item);
                        }
                    }
                    Yii::$app->session->setFlash('success', "Your selected favorite items have been added to your cart.");
                } else {
                    Yii::$app->session->setFlash('error', "Check the box next to the favorite item you wish to add to your cart.");
                }

            }

            if ($post['action'] == "moveto") {
                var_dump($post);
                die;
            }


            if ($post['action'] == "delete") {
                $favoriteList->is_deleted = true;
                if ($favoriteList->save(false)) {
                    return $this->redirect([StoreUrl::to('favorites/list/')]);
                }
            }

            return $this->redirect([StoreUrl::to('favorites/update/' . $id)]);
        }
        throw new HttpException(404, 'The requested Item could not be found.');
    }

    public function actionCsvUpload()
    {
        $fileArray = [];
        if (!$post = Yii::$app->request->post()) {
            return $this->redirect([StoreUrl::to('favorites/list')]);
        } else {
            $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');

            if (!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)) {
                if (is_uploaded_file($file = $_FILES['file']['tmp_name'])) {
                    $csvFile = fopen($file, 'r');
                    while (($line = fgetcsv($csvFile)) !== FALSE) {
                        if (count($line) == 2) {
                            $fileArray[] = $line;
                        }
                    }
                }
            }

            if (count($fileArray) > 0) {
                $errors = [];
                $withoutExt = preg_replace('/\\.[^.\\s]{3}$/', '', $_FILES['file']['name']);
                $list = new StoreFavoriteList();
                $list->title = $withoutExt;
                $list->customer_id = CurrentCustomer::getCustomerId();
                $list->created_at = time();

                if (!$list->save(false)) {
                    Yii::$app->session->setFlash('error', "An error occurred while creating your favorite list.");
                    return $this->redirect([StoreUrl::to('favorites/list')]);
                }
                foreach ($fileArray as $line) {
                    $productValue = CatalogAttributeValue::find()->where(["value" => $line[0], "attribute_id" => 4, 'store_id' => CurrentStore::getStoreId()])->orWhere(["value" => $line[0], "attribute_id" => 4, 'store_id' => 0])->one();
                    if ($productValue) {
                        $favorite_item = StoreFavoriteListItem::find()->where([
                            'list_id' => $list->list_id,
                            'product_id' => $productValue->product_id,
                            'sku' => $productValue->value
                        ])->one();

                        if (isset($favorite_item) && !empty($favorite_item)) {
                            $favorite_item->qty = $line[1];
                        } else {
                            $favorite_item = new StoreFavoriteListItem();
                            $favorite_item->list_id = $list->list_id;
                            $favorite_item->sku = $productValue->value;
                            $favorite_item->product_id = $productValue->product_id;
                            $favorite_item->qty = $line[1];
                        }

                        if (!$favorite_item->save(false)) {
                            Yii::$app->session->setFlash('error', "An error occurred while creating your favorite list.");
                            return $this->redirect([StoreUrl::to('favorites/list')]);
                        }
                    } else {
                        $errors[] = 'Sku "' . $line[0] . '" not found';
                    }
                }

                if (count($errors) > 0) {
                    $message = "";
                    foreach ($errors as $error) {
                        $message .= "<br>$error<br>";
                    }
                    Yii::$app->session->setFlash('error', "Error: $message");
                } else {
                    Yii::$app->session->setFlash('success', "favorite list import successful");
                }
                return $this->redirect([StoreUrl::to("favorites/update/$list->list_id")]);
            }
        }
    }

    public function actionCategoryModal()
    {
        if ($post = Yii::$app->request->post()) {
            $attribute_id = CatalogAttribute::findOne(['slug' => 'sku'])->id;
            $catalog_attribute = CatalogAttributeValue::findOne(['attribute_id' => $attribute_id, 'store_id' => CurrentStore::getStore(), 'value' => $post['sku']]);
            if ($catalog_attribute) {
                return Yii::$app->controller->renderPartial('/products/partials/_favorite-modal', ['id' => $catalog_attribute->product_id, 'sku' => $post['sku']]);
            }
        }
        return false;
    }
}
