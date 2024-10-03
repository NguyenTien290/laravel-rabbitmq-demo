<?php

namespace App\Http\Controllers\RabbitMQ;

use App\Http\Controllers\Controller;
use App\Services\RabbitMq\RabbitMQProducer;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function sendMessage()
    {
        $message = 'Hello i am RabbitMQ';
        $routing_key = 'hello';

        $producer = new RabbitMQProducer();
        $producer->sendMessage($message, $routing_key);
        $producer->close();

        return response()->json(['message' => 'Message sent to RabbitMQ']);
    }
}
