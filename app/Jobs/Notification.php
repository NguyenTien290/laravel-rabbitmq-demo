<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Notification implements ShouldQueue
{
    protected $notificationData;

    // Constructor để truyền dữ liệu cho Job
    public function __construct($notificationData)
    {
        $this->notificationData = $notificationData;
    }

    // Hàm handle() sẽ chứa logic xử lý tác vụ
    public function handle()
    {
        // Kết nối tới RabbitMQ
        $connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST'), 
            env('RABBITMQ_PORT'), 
            env('RABBITMQ_USER'), 
            env('RABBITMQ_PASSWORD')
        );
        $channel = $connection->channel();

        // Khai báo hàng đợi và gửi thông báo
        $channel->queue_declare('notifications_queue', false, true, false, false);

        $msg = new AMQPMessage(json_encode($this->notificationData));
        $channel->basic_publish($msg, '', 'notifications_queue');

        $channel->close();
        $connection->close();
    }
}
