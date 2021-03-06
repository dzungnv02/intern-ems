<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Webhook\Student' => [
            'App\Listeners\Webhook\StudentHook',
        ],
        'App\Events\Webhook\Classes' => [
            'App\Listeners\Webhook\ClassesHook',
        ],
        'App\Events\Webhook\Teacher' => [
            'App\Listeners\Webhook\TeacherHook',
        ],
        
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
