<?php 

namespace App\Services\RabbitMq;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQProducer {
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
       
    public function sendMessage($messageBody, $routingKey)
    {
        $exchange = 'direct_exchange'; // Type Exchange

        $this->chanel->exchange_declare($exchange, 'direct',false, true, false);

        // Create Message
        $message = new AMQPMessage($messageBody, ['delivery_mode' => 2]);

        // Send the message to the exchange with the routing key.
        $this->chanel->basic_publish($message, $exchange, $routingKey);

        echo "[x] Sent $messageBody to $routingKey\n";
    }

    public function close()
    {
        $this->chanel->close();
        $this->connection->close();
    }
}