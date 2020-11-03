<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use App\Invoice;


class PrintInvoice
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
}
