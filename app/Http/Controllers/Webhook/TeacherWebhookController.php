<?php
namespace App\Http\Controllers\Webhook;

use Illuminate\Http\Request;
use App\Events\Webhook\Teacher as TeacherEvent;
use Illuminate\Support\Facades\Log;

class TeacherWebhookController  extends WebhookController
{
    public function __invoke(Request $request)
    {
        $inputs = $request->all();
        $id = $inputs['id'];
        event(new TeacherEvent($inputs));
        return response()->json(['REQUEST_ID' => $id,'RESULT' => 'OK']); 
    }
}