<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

class Mail{
    private $api_key = 'b1eba48489da027abe514df507ac0a2d';
    private $api_key_secret = '29d6d0ff308eb518e39becf8a51adb36';

    public function send($to_email, $to_name, $subject, $title, $content,$published,$rejected,$end){
        $mj = new Client($this->api_key, $this->api_key_secret,true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "daoudousouffou30@gmail.com",
                        'Name' => "LEBONANGLE"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 4436229,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'title' => $title,
                        'content' => $content,
                        'published' => $published,
                        'rejected' => $rejected,
                        'end' => $end
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
    }

}