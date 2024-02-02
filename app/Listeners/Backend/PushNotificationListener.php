<?php

namespace App\Listeners\Backend;


use App\Classes\Fcm;
use App\Events\Backend\PushNotificationEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class PushNotificationListener implements ShouldQueue
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
    public function handle(PushNotificationEvent $event)
    {
        $deviceIds   = $event->deviceIds;
        $tit = $event->tit;
        $actiontype   = $event->actiontype;
        $refid   = $event->refid;
        $msg   = $event->msg;

        Fcm::sendPush($tit,$msg, $actiontype,$refid, $deviceIds);

    }
}
