<?php

namespace App\Mail;

use App\Models\Settings;
use App,DB;

class MasterMail
{

    static function masterMail(array $mailData)
    {
        $temp = DB::table('email_templates')->where('email_type','=',$mailData['email_type'])->first();
        $body = preg_replace("/{name}/", $mailData['name'] ,$temp->email_body);
        if(isset($mailData['verification_link'])){
            $body = preg_replace("/{verification_link}/", $mailData['verification_link'] ,$body);
        }

        if(isset($mailData['password_reset_link'])){
            $body = preg_replace("/{password_reset_link}/", $mailData['password_reset_link'] ,$body);
        }
        if(isset($mailData['order_status'])){
            $body = preg_replace("/{order_status}/", $mailData['order_status'] ,$body);
        }

        $data = [
            'email_body' => $body
        ];

        $objDemo = new \stdClass();
        if(isset($mailData['from_name'])){
            $objDemo->title = $mailData['from_name'];
        }
        if(isset($mailData['from_mail'])){
            $objDemo->from = $mailData['from_mail'];
        }

        $objDemo->to = $mailData['to_email'];
        $objDemo->subject = $temp->email_subject;

        // try{
            \Mail::send('emails.mail',$data, function ($message) use ($objDemo) {
                $message->from($objDemo->from,$objDemo->title);
                $message->to($objDemo->to);
                $message->subject($objDemo->subject);
            });

        // }

        // catch (\Exception $e){

        // }

    }


}
