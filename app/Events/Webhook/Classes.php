<?php

namespace App\Events\Webhook;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Events\Event;


use Illuminate\Support\Facades\Log;

class Classes extends Event
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $input;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($input)
    {
        $this->input = $input;
        Log::debug('Classes Event fired!');
    }
}
