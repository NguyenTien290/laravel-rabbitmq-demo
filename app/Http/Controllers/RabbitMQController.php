<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessMassage;
use Illuminate\Http\Request;

class RabbitMQController extends Controller
{
    public function sendMessage()
    {
        $message = 'Thông báo từ RabbitMQ Server';
        dispatch(new ProcessMassage($message));

        return response()->json(['status' => 'Message sent to RabbitMQ']);
    }
}
