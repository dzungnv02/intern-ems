<?php

namespace App\Listeners\Webhook;

use App\Events\Webhook\Student as StudentHookEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\CrmServices\StudentSync;

use Illuminate\Support\Facades\Log;


class StudentHook implements ShouldQueue
{
    use Queueable;

    protected $student_sync;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->student_sync = new StudentSync;
    }

    /**
     * Handle the event.
     *
     * @param  StudentHookEvent  $event
     * @return void
     */
    public function handle(StudentHookEvent $event)
    {
        $act = $event->input['act'];

        //var_dump($act);
        $this->student_sync->$act($event->input['id']);
        //Log::debug('Student Event Listener fired!');
    }
}
