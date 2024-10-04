<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class SendMessageCommand extends Command
{

    
    // protected $signature = 'send:message {message}';
    // protected $description = 'Send a message to RabbitMQ';

    // Sử dụng Fanout Exchange
    // protected $signature = 'send:message {message} {routingKey}';
    // protected $description = 'Send a message to RabbitMQ using Fanout Exchange';

    // Sử dụng Fanout Exchange
    protected $signature = 'send:message {message}';
    protected $description = 'Send a message to RabbitMQ using Direct Exchange';
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $connection = new AMQPStreamConnection(env('RABBITMQ_HOST'), env('RABBITMQ_PORT'), env('RABBITMQ_USER'), env('RABBITMQ_PASSWORD'));
        $channel = $connection->channel();

        // $exchange = 'direct_exchange'; // Type Exchange

        // Khởi tạo 1 queue và cấu hình 
        // $channel->queue_declare('hello', false, true, false, false, false, []);

        // Khai báo Direct Exchange
        // $channel->exchange_declare('direct_logs', 'direct', false, true, false);

        // Khai báo Fanout Exchange
        $channel->exchange_declare('logs', 'fanout', false, true, false);
    

        // Create Message
        $message = new AMQPMessage($this->argument('message'));

        // Lấy routing key từ tham số
        // $routingKey = $this->argument('routingKey');

        $channel->basic_publish($message, 'logs');

        // Send the message to the exchange with the routing key.
        // $this->chanel->basic_publish($message, $exchange, $routingKey);


        $this->info('Message sent: ' . $this->argument('message'));

        $channel->close();
        $connection->close();
    }
}
