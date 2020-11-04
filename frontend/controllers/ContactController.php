<?php

namespace frontend\controllers;

use app\components\StoreUrl;
use common\components\CurrentStore;
use common\components\Notify;
use Yii;
use yii\helpers\VarDumper;
use yii\web\Controller;
use frontend\models\ContactForm;
use common\models\settings\SettingsStore;

class ContactController extends Controller
{

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new ContactForm();
        $storeSettings = SettingsStore::getSettings();
        $settings = SettingsStore::find()->store()->one();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $data = [
                "personalizations" => [[
                    'to' => [
                        [
                            "email" => ($settings && $settings->general_email) ? $settings->general_email : 'kim@smeincusa.com',
                            "name" => CurrentStore::getStore()->name
                        ],
                        [
                            "email" => 'kim@smeincusa.com',
                            "name" => 'Kim Reyna'
                        ]

                    ],

                    'dynamic_template_data' => [
                        "store_url" => CurrentStore::getStore()->url,
                        "name"    => $model->name,
                        "email"   => $model->email,
                        "comment" => $model->body,
                    ],
                ]],
                "from" => [
                    "email" => Yii::$app->params['from_email']['address'],
                    "name" => Yii::$app->params['from_email']['name']
                ],
                "template_id" => "d-970cfbbb10eb4abeb91edc1cf6f563fd"
            ];

            if ($mailstatus = Notify::sendJsonMail($data)) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                error_log($mailstatus);
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            }

            return $this->redirect(StoreUrl::to('contact?success=true'));
        } else {
            return $this->render('index', [
                'model' => $model,
                'storeSettings' => $storeSettings
            ]);
        }
    }

}