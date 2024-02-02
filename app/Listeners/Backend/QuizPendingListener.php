<?php

namespace App\Listeners\Backend;

use App\Events\Backend\QuizPendingEvent;
use App\Notifications\Backend\QuizPendingNotification;

class QuizPendingListener
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
     * @param  ContractorCreate  $event
     * @return void
     */
    public function handle(QuizPendingEvent $event)
    {
        $joey = $event->joey;
        $joey->notify(new QuizPendingNotification());

    }
}
