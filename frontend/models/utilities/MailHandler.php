<?php
namespace frontend\models\utilities;

use Yii;

class MailHandler{

    /**
     * Send an email
     * @param $email_to
     * @param $email_subject
     * @param $email_content
     * @param null $email_layout
     */
    public static function send($email_to, $email_subject, $email_content, $from_email=null){

        if($email_to){
            if( is_array($email_to) ) {
                foreach($email_to as $e){
                    MailHandler::sendEmail($e, $email_subject, $email_content, $from_email);
                }
            }else{
                MailHandler::sendEmail($email_to, $email_subject, $email_content, $from_email);
            }
        }

        return true;
    }

    /**
     * @param @required $to
     * @param @required $subject
     * @param @required $content
     * @param string $from = info@smeincusa.com
     * @param string $layout = general
     */
    protected function sendEmail($to, $subject, $content, $from='info@smeincusa.com', $layout='general'){
        if( filter_var($to, FILTER_VALIDATE_EMAIL) ){
            $from_email = $from ? $from : 'info@smeincusa.com';

            Yii::$app->mailer->compose($layout, ['email_content' => $content])
                ->setFrom([$from_email => "SME INC USA"])
                ->setTo($to)
                ->setSubject($subject)
                ->send();
        }
    }

}