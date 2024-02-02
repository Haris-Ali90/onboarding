<?php

namespace App\Listeners\Backend;

use App\Events\Backend\NotTrainedEvent;
use App\Notifications\Backend\NotTrainedNotification;

class NotTrainedListener
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
    public function handle(NotTrainedEvent $event)
    {
        $joey = $event->joey;
        $joey->notify(new NotTrainedNotification());

    }
}
