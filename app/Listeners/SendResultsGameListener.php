<?php

namespace App\Listeners;

use App\Events\ResultsGame;
use App\Events\PodcastProcessed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendResultsGameListener
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
     * @param ResultsGame $event
     *
     * @return void
     */
    public function handle(ResultsGame $event)
    {
        return 'abc';
    }
}
