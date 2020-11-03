<?php
namespace App\Http\Controllers\Webhook;

use Illuminate\Http\Request;
use App\Events\Webhook\Classes as ClassesEvent;

use Illuminate\Support\Facades\Log;

class ClassesWebhookController  extends WebhookController
{
    public function __invoke(Request $request)
    {
        $inputs = $request->all();
        $id = $inputs['id'];
        $act = $inputs['act'];

        Log::debug('ClASSES HOOK:');
        Log::debug(var_export($inputs, true));

        event(new ClassesEvent($inputs));

        return response()->json(['REQUEST_ID' => $id,'RESULT' => 'OK']);  
    }
}