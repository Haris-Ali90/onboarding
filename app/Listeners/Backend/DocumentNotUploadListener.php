<?php

namespace App\Listeners\Backend;


use App\Events\Backend\DocumentNotUploadEvent;
use App\Notifications\Backend\DocumentNotUploadNotification;

class DocumentNotUploadListener
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
    public function handle(DocumentNotUploadEvent $event)
    {
        $joey = $event->joey;
        $joey->notify(new DocumentNotUploadNotification());

    }
}
