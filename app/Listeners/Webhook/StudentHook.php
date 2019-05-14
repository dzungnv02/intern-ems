<?php

namespace App\Listeners\Webhook;

use App\Events\Webhook\Student as StudentHookEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Support\Facades\Log;


class StudentHook implements ShouldQueue
{
    use Queueable;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  StudentHookEvent  $event
     * @return void
     */
    public function handle(StudentHookEvent $event)
    {
        Log::debug('Student Event Listener fired!');
    }
}
