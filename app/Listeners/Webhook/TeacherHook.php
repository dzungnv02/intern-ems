<?php

namespace App\Listeners\Webhook;

use App\Events\Webhook\Teacher as TeacherHookEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\CrmServices\TeacherSync;

use Illuminate\Support\Facades\Log;


class TeacherHook implements ShouldQueue
{
    use Queueable;

    protected $teacher_sync;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->teacher_sync = new TeacherSync;
    }

    /**
     * Handle the event.
     *
     * @param  StudentHookEvent  $event
     * @return void
     */
    public function handle(TeacherHookEvent $event)
    {
        $act = $event->input['act'];

        var_dump($act);
        $this->teacher_sync->$act($event->input['id']);
        Log::debug('Teacher Event Listener fired!');
    }
}
