<?php

namespace App\Http\Controllers;

use Log;
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
        Log::debug("Debug");
        Amqp::publish('ems', 'My Message' , ['queue' => 'ems_zohocrm_sync']);
        return response()->json(['RESULT' => 'OK']);  
    }
}
