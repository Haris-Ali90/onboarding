<?php

namespace App\Events\Backend;


use Illuminate\Queue\SerializesModels;


class DocumentNotUploadEvent
{
    use SerializesModels;

    public  $joey;

    /**
     * Create a new event instance.
     * @param string $password
     * @return void
     */
    public function __construct($joey)
    {
        $this->joey = $joey;
    }

}
