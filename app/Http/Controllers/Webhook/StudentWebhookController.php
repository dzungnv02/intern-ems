<?php
namespace App\Http\Controllers\Webhook;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StudentWebhookController  extends WebhookController
{
    public function add_student(Request $request)
    {
        $inputs = $request->all();
        $id = $inputs['id'];
        
        Log::debug('ADD STUDENT HOOK:');
        Log::debug(var_export($inputs, true));

        return response()->json(['REQUEST_ID' => $id,'RESULT' => 'OK']);    
    } 

    public function edit_student(Request $request)
    {
        $inputs = $request->all();
        $id = $inputs['id'];

        Log::debug('EDIT STUDENT HOOK:');
        Log::debug(var_export($inputs, true));
        return response()->json(['REQUEST_ID' => $id,'RESULT' => 'OK']);
    }

    public function delete_student(Request $request)
    {
        $inputs = $request->all();
        $id = $inputs['id'];
        Log::debug('DELETE STUDENT HOOK:');
        Log::debug(var_export($id, true));
        return response()->json(['REQUEST_ID' => $id,'RESULT' => 'OK']);
    }
}