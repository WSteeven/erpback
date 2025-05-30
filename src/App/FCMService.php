<?php

namespace Src\App;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FCMService{
    protected $messaging;

    public function __construct()
    {
        $factory = (new Factory)->withServiceAccount(base_path('storage/firebase/firebase_credentials.json'));

        $this->messaging = $factory->createMessaging();
    }

    public function sendTo($deviceToken, $title, $body){
        $message = CloudMessage::withTarget('token', $deviceToken)
        ->withNotification(Notification::create($title, $body));

        return $this->messaging->send($message);
    }
    public function sendDataTo($deviceToken, $title, $body){
        $message = CloudMessage::withTarget('token', $deviceToken)
        ->withData(['title'=>$title, 'body'=>$body, 'other'=>'Otro valor']);

        return $this->messaging->send($message);
    }
}
