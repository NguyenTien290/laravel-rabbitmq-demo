<?php

use App\Http\Controllers\RabbitMQController;
use App\Jobs\ProcessMassage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use VladimirYuldashev\LaravelQueueRabbitMQ\Queue\Connectors\RabbitMQConnector;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/send-message', function() {
    ProcessMassage::dispatch('Hello RabbitMq');

    return 'Message send to RabbitMq';
});

// URL gửi đến client
// Route::get('send-message', [RabbitMQController::class, 'sendMessage']);

