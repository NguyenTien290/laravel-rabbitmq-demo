<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService
{

    protected $connection;
    protected $channel;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(
            env('APP_URL'),
            env('RABBITMQ_PORT'),
            env('RABBITMQ_USER'),
            env('RABBITMQ_PASSWORD'),
        );

        $this->channel = $this->connection->channel();
    }


    public function publish($message)
    {
        $this->channel->queue_declare('notifications', false, true, false, false);

        $msg = new AMQPMessage($message);
        $this->channel->basic_publish($msg, '', 'notifications');
    }

    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }
}
