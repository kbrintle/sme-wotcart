<?php

namespace common\components;

use Yii;

class Notify
{

    public static function sendMail($subject, $recipients, $template, $data){

        $email_from_address = Yii::$app->params['from_email']['address'];
        $email_from_name    = Yii::$app->params['from_email']['name'];

        $sendgrid = new \SendGrid('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');
        $email = new \SendGrid\Mail\Mail();
        $email->setFrom($email_from_address,  $email_from_name);
        $email->setSubject($subject);
        $email->addTo($recipients);
        $email->setTemplateId($template);


        if(isset($data) && is_array($data)){
            foreach ($data as $k=>$v){
                //print_r($data);
                $email->addDynamicTemplateData($k, $v);
            }
        }
        try {


            $response = $sendgrid->send($email);
            if($response->statusCode() === 202){
                return true;
            }
        } catch (Exception $e) {
            return false;
        }
    }
    public static function sendNotification($recipent, $subject, $template, $data) {
        $to = array();
        foreach ($recipent as $email=>$firstname) {
            $to[] = array('email'=>$email,'name'=>$firstname);

        }

        $template_data = [
            "personalizations" => [[
                'to'=> $to,
                'dynamic_template_data' => $data,
            ]],
            "from" => [
                "email" => Yii::$app->params['from_email']['address'],
                "name" => Yii::$app->params['from_email']['name']
            ],
            "subject" => $subject,
            "template_id" => $template
        ];

        $data_string = json_encode($template_data);

        $ch = curl_init('https://api.sendgrid.com/v3/mail/send');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Authorization: Bearer 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',
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
        );

        $results = curl_exec($ch);
        return $results;

    }
    public static function sendJsonMail($data){


        $data_string = json_encode($data);
        //print_r($data_string); die;

        $ch = curl_init('https://api.sendgrid.com/v3/mail/send');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Authorization: Bearer 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',
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
        );

        $result = curl_exec($ch);
        //print_r($result); die;
        return true;
    }
}

