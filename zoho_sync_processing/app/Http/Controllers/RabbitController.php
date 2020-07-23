<?php

namespace App\Http\Controllers;

use Amqp;

class RabbitController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function __invoke()
    {
        // $inputs = $request->all();
        // $id = $inputs['id'];
        // $act = $inputs['act'];

        // Log::debug('ClASSES HOOK:');
        // Log::debug(var_export($inputs, true));

        // event(new ClassesEvent($inputs));

        return response()->json(['RESULT' => 'OK']);  
    }
}
