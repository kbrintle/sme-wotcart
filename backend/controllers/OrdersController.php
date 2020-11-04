<?php

namespace backend\controllers;

use common\models\sales\SalesOrderPayment;
use common\models\sales\SalesOrderStatus;
use common\models\sales\SalesQuote;
use kartik\mpdf\Pdf;
use Yii;
use common\models\sales\SalesOrder;
use common\models\sales\search\SalesOrderSearch;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\sales\SalesOrderItem;
use common\models\catalog\CatalogProduct;
use common\models\sales\SalesOrderAddress;
use common\models\core\CountryRegion;
use common\models\settings\SettingsStore;
use common\components\Notify;

/**
 * OrdersController implements the CRUD actions for Order model.
 */
class OrdersController extends Controller
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
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SalesOrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Order model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $order = $this->findModel($id);

        if (Yii::$app->request->post()) {
            $qty = $_POST['qty'];
            foreach ($qty as $k => $q) {
                SalesOrderItem::recalculate($k, $q);
            }
            SalesOrder::recalculate($order->id);
            if (isset($_POST['purchase_order'])) $order->purchase_order = $_POST['purchase_order'];
            if (isset($_POST['customer_note'])) $order->customer_note = $_POST['customer_note'];

            $order->save(false);

            $order = $this->findModel($id);
        }

        return $this->render('view', [
            'order' => $order,
            'customer' => $order->customer,
            'store' => $order->store,
            'payment' => $order->payment,
            'billingAddress' => $order->billingAddress,
            'shippingAddress' => $order->shippingAddress,
        ]);
    }

    /**
     * Creates a new Order model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SalesOrder();

        if ($model->load(Yii::$app->request->post())) {
            $status = SalesOrderStatus::findOne(['name' => 'Pending']);
            $model->status = $status ? $status->order_status_id : null;

            if ($model->save())
                return $this->redirect(['view', 'id' => $model->order_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Order model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->order_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionProcess($id, $status)
    {
        $model = $this->findModel($id);

        if ($model && $status) {
            switch ($status) {
                case 'accept':
                    $status = SalesOrderStatus::findOne(['name' => 'Processing']);
                    $model->status = $status ? "$status->order_status_id" : null;

                    //Email Approval Confirmation

                    $this->confirmationEmail($model->id, SalesOrder::getConfirmationRecipients($model->id, 'admin'));
//                    $this->confirmationEmail($model->getSalesOrder(), SalesOrder::getConfirmationRecipients($model->getSalesOrder(), 'store'));
//                    $this->confirmationEmail($model->getSalesOrder(), SalesOrder::getConfirmationRecipients($model->getSalesOrder(), 'user'));
                    $this->confirmationEmail($model->id, SalesOrder::getConfirmationRecipients($model->id, 'supervisor', true));

                    break;
                case 'complete':
                    $status = SalesOrderStatus::findOne(['name' => 'Delivered']);
                    $model->status = $status ? "$status->order_status_id" : null;
                    break;
            }

            if ($model->save())
                return $this->redirect(['index']);
        }

        return $this->redirect(['index']);
    }

    public static function getDisplayOrderStatus($id)
    {
        $model = SalesOrder::findOne(['order_id' => $id]);

        if ($model) {
            $status = SalesOrderStatus::findOne($model->status);

            if ($status) {
                $active = '<i class="material-icons active">check_circle</i>';
                $complete = '<i class="material-icons active">lens</i>';
                $inactive = '<i class="material-icons inactive">lens</i>';
                $steps = [$complete, $inactive, $inactive];

                switch ($status->name) {
                    case 'Processing':
                        $width = 50;
                        $steps[1] = $active;
                        break;
                    case 'Delivered':
                        $width = 100;
                        $steps[1] = $complete;
                        $steps[2] = $active;
                        break;

                    default:
                        $width = 0;
                        break;
                }

                $output = '<div class="order-status">';
                $output .= "<div class='progress'><div class='progress-bar' style='width: $width%'></div></div>";
                $output .= '<div class="row order-steps hidden-print">';
                $output .= '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 order-step step-1">' . $steps[0] . '<br />Pending</div>';
                $output .= '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 order-step step-2">' . $steps[1] . '<br />Processing</div>';
                $output .= '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 order-step step-3">' . $steps[2] . '<br />Delivered</div>';
                $output .= '</div></div>';

                return $output;
            }
        }

        return '';
    }

    /**
     * Deletes an existing Order model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Cancels an existing Order model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionCancel($id)
    {
        if (($order = SalesOrder::findOne(['order_id' => $id])) && ($salesOrderStatus = SalesOrderStatus::findOne(['name' => 'Canceled']))) {
            $order->status = $salesOrderStatus->order_status_id;
            $order->save(false);
            return $this->redirect(['index']);
        }
    }

    /**
     * Notifies customer of an existing Order model.
     * @param integer $id
     * @return mixed
     */
    public function actionEmailConfirmation($id)
    {


    }


    public function confirmationEmail($id = 0, $recipents)
    {
        $order = SalesOrder::findOne($id);

        $itemArray = [];
        foreach ($order->items as $item) {

            $name = CatalogProduct::getName($item->product_id);
            $options = CatalogProduct::getProductCustomOptions($item->product_id, $item->sku);
            foreach ($options as $option) {
                $name .= " - $option";
            }

            $itemArray[] =
                [
                    'name' => $name,
                    'sku' => $item->sku,
                    'qty' => $item->qty_ordered,
                    'item_price' => $item->price,
                    'item_subtotal' => $item->subtotal
                ];
        }

        $billing = SalesOrderAddress::find()->where(['id' => $order->billing_address_id])->one();
        $shipping = SalesOrderAddress::find()->where(['id' => $order->shipping_address_id])->one();

        if (isset($billing)) {

            $billing_address =
                [
                    "firstname" => $billing->firstname,
                    "lastname" => $billing->lastname,
                    "address" => $billing->street,
                    "city" => $billing->city,
                    "state" => CountryRegion::getRegionById($billing->region_id)->code,
                    "zip" => $billing->postcode,
                    "phone" => $billing->telephone,
                ];
        }
        if (isset($shipping)) {
            $shipping_address = [
                "firstname" => $shipping->firstname,
                "lastname" => $shipping->lastname,
                "address" => $shipping->street,
                "city" => $shipping->city,
                "state" => CountryRegion::getRegionById($shipping->region_id)->code,
                "zip" => $shipping->postcode,
                "phone" => $shipping->telephone,
            ];
        }
        if ($order) {


            if (isset($recipents) && !empty($recipents[0])) {
                $data = [
                    "personalizations" => [[
                        'to' => $recipents,
                        'dynamic_template_data' => [
                            'logo' => SettingsStore::getLogo(true),
                            "order_id" => $order->order_id,
                            "total" => $order->grand_total,
                            "subtotal" => $order->subtotal,
                            "shipping" => $order->shipping_amount,
                            "discount" => ($order->discount_amount > 0) ? $order->discount_amount : null,
                            "reward_discount" => ($order->discount_reward_amount > 0) ? $order->discount_reward_amount : null,
                            "tax" => $order->tax_amount,
                            "customer_firstname" => $order->customer_firstname,
                            "customer_lastname" => $order->customer_lastname,
                            "customer_email" => $order->customer_email,
                            "items" => $itemArray,
                            "billing" => $billing_address,
                            "shipping" => $shipping_address,
                            "purchase_order" => $order->purchase_order,

                            "notes" => $order->customer_note
                        ],
                    ]],
                    "from" => [
                        "email" => Yii::$app->params['from_email']['address'],
                        "name" => Yii::$app->params['from_email']['name']
                    ],

                    "template_id" => "d-19fe39e83bd348ac8edcea41e83680e4"
                ];
                //print_r($data); die;
                if (Notify::sendJsonMail($data)) {
                    return true;
                }
            }
        }
        return true;

    }

    public function actionCreateInvoice($id)
    {
        $this->view->title = "Invoice #" . $id;
        $this->layout = "sign_up";
        $order = SalesOrder::findOne(['order_id' => $id]);

        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('/orders/_pdf', ['order' => $order]);

        // http response
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',

            //'cssFile' => '@webroot/css/styles.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
            // set mPDF properties on the fly
            'options' => [],
            // call mPDF methods on the fly
            'methods' => [
                'SetHeader' => [''],
                //'SetFooter'=>['<div class="in-pdf-ftr text-center"><span>Invoice created with <img src="https://www.brewfully.com/images/Logos/BF_Logo_W.svg" alt="Brewfully" width="100px"/></span></div>'],
            ]
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SalesOrder::findOne(['order_id' => $id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}