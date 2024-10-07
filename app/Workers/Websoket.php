<?php 

namespace App\Workers;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use SplObjectStorage;

class Websoket implements MessageComponentInterface{

    protected $clients;

    public function __construct()
    {
        $this->clients = new SplObjectStorage;

        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();
        $channel->queue_declare('message', false, false, false, false);

        $callback = function ($msg){
            foreach ($this->clients as $client) {
                $client->send($msg->body);
            }
        };

        $channel->basic_consume('message', '', false, true, false, false, $callback);

        while(count($channel->callbacks)){
            $channel->wait();
        }

        // Đóng kết nối
        $channel->close();
        $connection->close();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        // Không làm gì ở đây vì RabbitMQ sẽ xử lý.
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        $conn->close();
    }
}