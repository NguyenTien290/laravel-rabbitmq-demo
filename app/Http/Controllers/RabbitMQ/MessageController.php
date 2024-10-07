<?php

namespace App\Http\Controllers\RabbitMQ;

use App\Events\MessageSentEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Queue;

class MessageController extends Controller
{
    public function sendMessage(Request $request)
    {
        $message = $request->input('message');
        event(new MessageSentEvent($message));

        // Optionally push to RabbitMQ queue
        Queue::connection('rabbitmq')->pushRaw($message);

        return response()->json(['status' => 'Message sent!']);
    }
}
