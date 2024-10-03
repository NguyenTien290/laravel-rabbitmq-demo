<?php 

namespace App\Console\Commands;

use App\Services\RabbitMq\RabbitMQConsumer;
use Illuminate\Console\Command;

class ConsumeRabbitMQ extends Command{
    protected $signature = 'rabbitmq:consume';

    public function handle()
    {
        $consumer = new RabbitMQConsumer();
        $consumer->consume(env('RABBITMQ_QUEUE'));
        $consumer->close();
    }
}