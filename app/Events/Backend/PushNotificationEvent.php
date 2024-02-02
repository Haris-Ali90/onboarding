<?php

namespace App\Events\Backend;


use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;


class PushNotificationEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public  $deviceIds, $msg, $tit,$actiontype,$refid;

    /**
     * Create a new event instance.
     * @param string $password
     * @return void
     */
    public function __construct($tit,$msg,$actiontype,$refid, $deviceIds)
    {
        $this->deviceIds = $deviceIds;
        $this->msg = $msg;
        $this->tit = $tit;
        $this->actiontype = $actiontype;
        $this->refid = $refid;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
