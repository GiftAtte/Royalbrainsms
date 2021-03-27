<?php

namespace App\Listeners;

use App\Events\MarksCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateResults
{
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
     * @param  MarksCreated  $event
     * @return void
     */
    public function handle(MarksCreated $event)
    {
        //
    }
}
