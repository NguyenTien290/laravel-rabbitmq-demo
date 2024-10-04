<?php

namespace App\Http\Controllers\RabbitMQ;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function sendMessage(Request $request)
    {   
        $request->validate([
            'message' => 'required|string|max:255',
        ]);

        $message = $request->input('message');
    }
}
