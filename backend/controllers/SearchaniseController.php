<?php

namespace backend\controllers;

use app\models\CatalogStorebrands;
use backend\components\CurrentUser;
use common\components\helpers\PermissionHelper;
use common\models\catalog\CatalogProduct;
use common\models\catalog\search\CatalogBrandSearch;
use Yii;
use common\models\catalog\CatalogBrandStore;
use common\models\catalog\CatalogBrand;
use yii\web\Controller;
use yii\helpers\VarDumper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\CurrentStore;
use common\models\core\Store;
use yii\web\UploadedFile;

/**
 * SearchaniseController i
 */
class SearchaniseController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all CatalogBrand models.
     * @return mixed
     */
    public function actionIndex()
    {

        return $this->render('index', []);
    }
}