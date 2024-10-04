<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

class SendMessageCommand extends Command
{

    
    // protected $signature = 'send:message {message}';
    // protected $description = 'Send a message to RabbitMQ';

    // Sử dụng Redict Exchange
    // protected $signature = 'send:message {message} {routingKey}';

    // Topic Exchange
    protected $signature = 'send:message {message} {topic}';
    protected $description = 'Send a message to RabbitMQ using Topic Exchange';

    // Header Exchange
    // protected $signature = 'send:message {message} {headerKey} {headerValue}';
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

        // $connection = new AMQPStreamConnection(env('RABBITMQ_HOST'), env('RABBITMQ_PORT'), env('RABBITMQ_USER'), env('RABBITMQ_PASSWORD'));
        // $channel = $connection->channel();

        // $exchange = 'direct_exchange'; // Type Exchange

        // Khởi tạo 1 queue và cấu hình 
        // $channel->queue_declare('hello', false, true, false, false, false, []);

        // Khai báo Direct Exchange
        // $channel->exchange_declare('direct_logs', 'direct', false, true, false);

        
        // Khai báo Fallout Exchange
        // $channel->exchange_declare('fallout_logs', 'fallout', false, true, false);

        // Khai báo Topic Exchange
        // $channel->exchange_declare('topic_logs', 'topic', false, true, false);
    
        
        //Khai báo Header Exchange
        // $channel->exchange_declare('headers_logs', 'headers', false, true, false);

        // Create Message
        // $message = new AMQPMessage($this->argument('message'));

        
        // $headerKey = $this->argument('headerKey');
        // $headerValue = $this->argument('headerValue');

        // $topic = $this->argument('topic');

        // $header = new AMQPTable([
        //     $headerKey => $headerValue,
        // ]);

        // $message->set('application_headers', $header);


        // $channel->basic_publish($message, 'topic_logs','topic');

        // Send the message to the exchange with the routing key.
        // $this->chanel->basic_publish($message, $exchange, $routingKey);

        // $this->info('Message sent: ' . $topic);

        // $channel->close();
        // $connection->close();

         // Kết nối đến RabbitMQ
         $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
         $channel = $connection->channel();
 
         // Khai báo Topic Exchange
         $channel->exchange_declare('topic_logs', 'topic', false, true, false);
 
         // Tạo message từ tham số đầu vào
         $message = new AMQPMessage($this->argument('message'));
 
         // Lấy topic từ tham số
         $topic = $this->argument('topic');
 
         // Gửi message đến Topic Exchange với topic
         $channel->basic_publish($message, 'topic_logs', $topic);
 
         $this->info('Message sent with topic: ' . $topic);
 
         // Đóng kết nối
         $channel->close();
         $connection->close();
    }
}
