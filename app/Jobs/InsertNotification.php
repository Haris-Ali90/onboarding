<?php

namespace App\Jobs;

use App\Models\Notification;
use App\Models\UserDevice;
use Faker\Provider\DateTime;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Classes\Fcm;
use Illuminate\Support\Facades\DB;

class InsertNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $subject, $message, $click_action,$joey_ids;

    /**
     * SendPushNotification constructor.
     * @param $msg
     * @param $deviceToken
     * @param $extraData
     */
    public function __construct($subject, $message, $click_action,$joey_ids)
    {
        $this->subject = $subject;
        $this->message = $message;
        $this->click_action = $click_action;
        $this->joey_ids = $joey_ids;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $createNotification = [];

        $payload =['notification'=> ['title'=> $this->subject,'body'=> $this->message,'click_action'=> $this->click_action],
            'data'=> ['data_title'=> $this->subject,'data_body'=> $this->message,'data_click_action'=> $this->click_action]];

        UserDevice::whereIn('user_id',$this->joey_ids)->chunk(1000, function ($devices) {
            $deviceIds = $devices->pluck('device_token');
            $job = new SendPushNotification($this->subject,$this->message,$this->click_action,$refid = null, $deviceIds);
            dispatch($job);
        });
        foreach ($this->joey_ids as $id){
            $createNotification[] = [
                'user_id' => $id,
                'user_type'  => 'Joey',
                'notification'  => $this->subject,
                'notification_type'  => 'admin-notification',
                'notification_data'  => json_encode(["body"=> $this->message]),
                'payload'            => json_encode($payload),
                'is_silent'          => 0,
                'is_read'            => 0,
                'created_at'                  => date('Y-m-d H:i:s')
            ];
        }
        Notification::insert($createNotification);
    }
}
