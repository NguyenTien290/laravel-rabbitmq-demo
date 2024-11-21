<?php

namespace App\Http\Controllers;

use App\Jobs\SendNotificationJob;
use Illuminate\Http\Request;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class NotificationController extends Controller
{
    public function sendNoti(Request $request)
    {

        $connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST'),
            env('RABBITMQ_PORT'),
            env('RABBITMQ_USER'),
            env('RABBITMQ_PASSWORD'),
        );

        $channel = $connection->channel();

        // Khai báo 1 queue
        $channel->queue_declare('notification', false, true, false, true);

        $messageBody = $request->input('message');

        $msg = new AMQPMessage($messageBody);

        $channel->basic_publish($msg, '', 'notification');

        $channel->close();
        $connection->close();

        return response()->json(['message' => 'Thành công'], 200);
    }
}
