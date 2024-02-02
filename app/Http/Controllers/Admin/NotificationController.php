<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Fcm;
use App\Events\Backend\PushNotificationEvent;
use App\Events\NotificationEvent;
use App\Jobs\InsertNotification;
use App\Jobs\SendCustomerEmailJob;
use App\Jobs\SendPushNotification;
use App\Models\JoeyDocumentVerification;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */


    public function __construct()
    {
        $this->middleware('auth:admin');
        parent::__construct();
    }


    /**
     * Show Notification Form
     *
     */
    public function showNotification()
    {
       $joeys = JoeyDocumentVerification::whereHas('agreementUser', function ($query) {
            $query->whereNotNull('signed_at');
        })->where('is_enabled',1)->whereNull('deleted_at')->whereNotNull('plan_id')->get(['id','first_name','last_name']);
       /* foreach ($joeys_get as $joey) {
            $agreementCheck = AgreementUser::where('user_id', '=', $joey->id)
                ->where('user_type', '=', 'joeys')
                ->first();
            if ($agreementCheck) {
                if ($agreementCheck->signed_at != null) {
                    $joeys[] = $joey;
                }
            }
        }*/
        return view('admin.notification.joey_notification',compact('joeys'));
    }

    /**
     * Send Notification to selected customers
     *
     */
    public function sendNotification(Request $request)
    {

        $data = $request->all();

       $job = new InsertNotification($data['subject'],  $data['message'],'admin-notification',$data['joey_ids']);
       dispatch($job);

        /*$createNotification = [];

        $payload =['notification'=> ['title'=> $data['subject'],'body'=> $data['message'],'click_action'=> 'admin-notification'],
            'data'=> ['data_title'=> $data['subject'],'data_body'=> $data['message'],'data_click_action'=> 'admin-notification']];


        UserDevice::whereIn('user_id',$data['joey_ids'])->chunk(1000, function ($devices) use ($data) {
            $deviceIds = $devices->pluck('device_token');
            event(new PushNotificationEvent($data['subject'],$data['message'],'admin-notification',$refid = null, $deviceIds));
           // $job = new SendPushNotification($data['subject'],$data['message'],'admin-notification',$refid = null, $deviceIds);
           // dispatch($job);
        });
        event(new \App\Events\Backend\NotificationEvent($data['joey_ids'],$payload,$data['subject'],$data['message']));*/

        return redirect()
            ->route('notification.index')
            ->with('success', 'Notification has sent to joeys successfully!');
    }


}
