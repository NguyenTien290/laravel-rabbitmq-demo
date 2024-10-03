<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class SendMessageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:message {message}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a message to RabbitMQ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
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

        $channel->queue_declare('hello', false, true, false, false, false, []);

        // Create Message
        $message = new AMQPMessage($this->argument('message'));
        $channel->basic_publish($message, '', 'hello');

        // Send the message to the exchange with the routing key.
        // $this->chanel->basic_publish($message, $exchange, $routingKey);


        $this->info('Message sent: ' . $this->argument('message'));

        $channel->close();
        $connection->close();
    }
}
