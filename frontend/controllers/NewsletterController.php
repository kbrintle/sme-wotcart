<?php

namespace frontend\controllers;

use common\components\CurrentStore;
use Yii;
use yii\helpers\VarDumper;
use yii\web\Controller;
use common\models\store\StoreNewsletterSubscriber;
use common\components\Notify;

class NewsletterController extends Controller
{

    public function actionPost() {

    }

    public function actionSubscribe(){
        if(Yii::$app->request->isAjax){
            $data = Yii::$app->request->post();
            $email = $data['NewsletterForm']['email'];
            if($email){
                $newletterSubscriber = new StoreNewsletterSubscriber();
                $newletterSubscriber->email = $email;
                $newletterSubscriber->store_id = CurrentStore::getStoreId();
                $newletterSubscriber->is_active = true;
                $newletterSubscriber->created_time = time();

                if($newletterSubscriber->save(false)){
                    $this->notifySubscriber($newletterSubscriber);

                }
            }
        }
    }

    public function notifySubscriber($newletterSubscriber){
        Notify::sendMail('Welcome To BedHeads', [$newletterSubscriber->email], 'customer/newsletter_signup', $data=[

        ]);
    }
}