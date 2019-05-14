<?php
namespace App\Http\Controllers\Webhook;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ClassesWebhookController  extends WebhookController
{
    public function __invoke(Request $request)
    {
        $inputs = $request->all();
        $id = $inputs['id'];
        Log::debug('CLASSES HOOK:');
        Log::debug(var_export($inputs, true));
        return response()->json(['REQUEST_ID' => $id,'RESULT' => 'OK']); 
    }
}