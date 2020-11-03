<?php
namespace App\Http\Controllers\Webhook;

use Illuminate\Http\Request;
use App\Events\Webhook\Student as StudentEvent;
use Illuminate\Support\Carbon;

use Illuminate\Support\Facades\Log;

class StudentWebhookController  extends WebhookController
{
    public function __invoke(Request $request)
    {
        $inputs = $request->all();
        $id = $inputs['id'];
        $act = $inputs['act'];

        Log::debug('STUDENT HOOK:');
        Log::debug(var_export($inputs, true));

        event(new StudentEvent($inputs));

        return response()->json(['REQUEST_ID' => $id,'RESULT' => 'OK']); 
    }
}