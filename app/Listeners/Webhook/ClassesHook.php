<?php

namespace App\Listeners\Webhook;

use App\Events\Webhook\Classes as ClassesHookEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\CrmServices\ClassesSync;

use Illuminate\Support\Facades\Log;


class ClassesHook implements ShouldQueue
{
    use Queueable;

    protected $classes_sync;
    public $timeout = 360;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->classes_sync = new ClassesSync;
    }

    /**
     * Handle the event.
     *
     * @param  ClassesHookEvent  $event
     * @return void
     */
    public function handle(ClassesHookEvent $event)
    {
        $act = $event->input['act'];

        //var_dump($act);
        $this->classes_sync->$act($event->input['id']);
        //Log::debug('Classes Event Listener fired!');
    }
}
