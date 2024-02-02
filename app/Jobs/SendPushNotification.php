<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Classes\Fcm;
class SendPushNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $deviceIds, $msg, $tit,$actiontype,$refid;

    /**
     * SendPushNotification constructor.
     * @param $msg
     * @param $deviceToken
     * @param $extraData
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
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Fcm::sendPush($this->tit,$this->msg, $this->actiontype,$this->refid, $this->deviceIds);
    }
}
