<?php 

namespace App\Services\RabbitMq;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQConsumer {
    protected $connection;

    protected $chanel;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST'),
            env('RABBITMQ_PORT'),
            env('RABBITMQ_USER'),
            env('RABBITMQ_PASSWORD'),
        );

        $this->chanel = $this->connection->channel();
    }
       
    public function consume($queueName)
    {
        $this->chanel->queue_declare($queueName, false, true, false, false);

        $callback = function ($msg){
            echo ' [x] Received ', $msg->body, "\n";    
            // Process logic for message
        };

        $this->chanel->basic_consume($queueName, '', false, true, false, false, $callback);

        while($this->chanel->is_consuming()){
            $this->chanel->wait();
        }
    }
    public function close()
    {
        $this->chanel->close();
        $this->connection->close();
    }
}