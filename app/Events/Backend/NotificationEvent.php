<?php

namespace App\Events\Backend;


use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;


class NotificationEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public  $joeys,$payload,$subject,$message;

    /**
     * Create a new event instance.
     * @param string $password
     * @return void
     */
    public function __construct($joeys,$payload,$subject,$message)
    {
        $this->joeys   = $joeys;
        $this->payload   = $payload;
        $this->subject   = $subject;
        $this->message   = $message;
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
