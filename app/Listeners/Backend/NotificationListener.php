<?php

namespace App\Listeners\Backend;


use App\Events\Backend\NotificationEvent;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificationListener implements ShouldQueue
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
    public function handle(NotificationEvent $event)
    {
        $joeys   = $event->joeys;
        $payload   = $event->payload;
        $subject   = $event->subject;
        $message   = $event->message;

        $createNotification=[];
        foreach ($joeys as $id){
            $createNotification[] = [
                'user_id' => $id,
                'user_type'  => 'Joey',
                'notification'  => $subject,
                'notification_type'  => 'admin-notification',
                'notification_data'  => json_encode(["body"=> $message]),
                'payload'            => json_encode($payload),
                'is_silent'          => 0,
                'is_read'            => 0,
                'created_at'                  => date('Y-m-d H:i:s')
            ];
        }
        Notification::insert($createNotification);

    }
}
