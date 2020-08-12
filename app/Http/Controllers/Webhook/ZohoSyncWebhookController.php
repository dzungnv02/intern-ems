<?php
namespace App\Http\Controllers\Webhook;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Amqp;

class ZohoSyncWebhookController  extends WebhookController
{
    public function __invoke(Request $request)
    {
        $inputs = $request->all();
        $message = json_encode($inputs);
        Amqp::publish('ems', $message , ['queue' => env("RABBITMQ_QUEUE","ems_zohocrm_sync")]);
        return response()->json(['RESULT' => 'OK']); 
    }
}
