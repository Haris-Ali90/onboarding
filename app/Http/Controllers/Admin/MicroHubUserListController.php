<?php

namespace App\Http\Controllers\Admin;

use App\Classes\RestAPI;
use App\Http\Requests\Admin\UpdateHubRequest;
use App\Models\Cities;
use App\Models\DashboardUsers;
use App\Models\DeliveryProcessType;
use App\Models\HubProcess;
use App\Models\Hubs;
use App\Models\JoeycoUsers;
use App\Models\JoeyDocumentVerification;
use App\Models\Locations;
use App\Models\MicroHubJoeyAssign;
use App\Models\MicroHubPermission;
use App\Models\MicroHubRequest;
use App\Models\MiJobs;
use App\Models\MiJobsAssign;
use App\Models\OnboardingLoginIp;
use App\Models\User;
use App\Repositories\Interfaces\DashboardUsersRepositoryInterface;
use App\Repositories\Interfaces\HubProcessRepositoryInterface;
use App\Repositories\Interfaces\HubRepositoryInterface;
use App\Repositories\Interfaces\MiAssignJobsRepositoryInterface;
use App\Repositories\Interfaces\MicroHubJoeyAssignRepositoryInterface;
use App\Repositories\Interfaces\MicroHubRequestRepositoryInterface;
use App\Repositories\Interfaces\MicroHubPermissionRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Hub;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class MicroHubUserListController extends Controller
{
    private $HubRepository;
    private $HubProcessRepository;
    private $DashboardUsersRepository;
    private $MicroHubRequestRepository;
    private $MicroHubJoeyAssignRepository;
    private $MiAssignJobsRepositoryInterface;
    private $MicroHubPermissionInterface;

    public function __construct(HubRepositoryInterface $HubRepositoryInterface,
                                HubProcessRepositoryInterface $HubProcessRepositoryInterface,
                                DashboardUsersRepositoryInterface $DashboardUsersRepositoryInterface,
                                MicroHubRequestRepositoryInterface $MicroHubRequestRepositoryInterface,
                                MicroHubJoeyAssignRepositoryInterface $MicroHubJoeyAssignInterface,
                                MiAssignJobsRepositoryInterface $MiAssignJobsRepositoryInterface,
                                MicroHubPermissionRepositoryInterface $MicroHubPermissionInterface
    )
    {


        //$this->middleware('guest:web')->except('logout');
        parent::__construct();
        $this->HubRepository = $HubRepositoryInterface;
        $this->HubProcessRepository = $HubProcessRepositoryInterface;
        $this->DashboardUsersRepository = $DashboardUsersRepositoryInterface;
        $this->MicroHubRequestRepository = $MicroHubRequestRepositoryInterface;
        $this->MicroHubJoeyAssignRepository = $MicroHubJoeyAssignInterface;
        $this->MiAssignJobsRepositoryInterface = $MiAssignJobsRepositoryInterface;
        $this->MicroHubPermissionInterface = $MicroHubPermissionInterface;
    }

    /**
     * Index action
     *
     */
    public function index(Request $request)
    {
        dd($request->all());
        //Getting All Request Data
        $data = $request->all();
        //Get Email From Request
        $email = isset($data['email'])? $request->get('email'):'';
        //Get Phone From Request
        $phone = isset($data['phone'])? $request->get('phone'):'';
        //Get Status From Request
        $status = isset($data['status'])?$request->get('status'):'';

        return backend_view('micro-hub.microHubUsers.index',compact('email','phone','status'));
    }

    /**
     * Call DataTable For List
     *
     */
    public function data(DataTables $datatables, Request $request): JsonResponse
    {
        $query = JoeycoUsers::select('jc_users.*','micro_hub_request.area_radius','micro_hub_request.own_joeys','micro_hub_request.status')
            ->join('micro_hub_request', 'jc_users.id', '=', 'micro_hub_request.jc_user_id')
            ->whereNull('micro_hub_request.deleted_at')
            ->where('micro_hub_request.status','!=',1);
        //Filter Data By Email
        if ($request->email) {
            $query = $query->where('jc_users.email_address',$request->email);
        }
        //Filter Data By Phone
        if ($request->phone) {
            $query = $query->where('jc_users.phone_no',$request->phone);
        }
        if($request->status && $request->status != null)
        {
            $query = $query->where('micro_hub_request.status',$request->status);
        }
        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('area_radius', static function ($record) {
                if(isset($record->area_radius))
                {
                    return $record->area_radius.' Sq-yard';
                }
                return '';
            })
            ->editColumn('own_joeys', static function ($record) {
                if($record->own_joeys == 1)
                {
                    return 'Yes';
                }
                else
                {
                    return 'No';
                }
            })
            ->editColumn('status', static function ($record) {
                return backend_view('micro-hub.microHubUsers.status', compact('record') );

            })
            ->rawColumns(['status', 'phone', 'link', 'action'])
            ->make(true);
    }

    /**
     * Update Status
     *
     */
    public function statusUpdate(Request $request)
    {
        DB::beginTransaction();
        try {

            $statusData=$request->all();
            if($statusData['status'] == 1)
            {
                $redirect_url = route('micro-hub.profile-status.edit',$statusData['id']);

                return RestAPI::response(["handle_type"=> 'redirect',"redirect_url"=> $redirect_url],true,'','');

            }

            $updateStatus = [
                'status' => $statusData['status'],
            ];

            MicroHubRequest::where('jc_user_id' , $statusData['id'])->update($updateStatus);


            DB::commit();
            $route_url = route('micro-hub.users.index');
            return RestAPI::response(["handle_type"=> 'reload',"redirect_url"=> $route_url],true,'Status update successfully','');
        } catch (\Exception $e)
        {
            DB::rollback();

            Session::put('error', 'Sorry something went wrong. please try again later !');
            //return redirect()->route('customer-service.edit',$plan_id);
            return redirect()->route('micro-hub.users.index');
        }

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function profileStatusEdit($hub_user)
    {


        $hubUsers = JoeycoUsers::join('micro_hub_request', 'jc_users.id', '=', 'micro_hub_request.jc_user_id')
            ->where('jc_users.id',$hub_user)
            ->first();
        //dd($hubUsers);
        $user_data =  DashboardUsers::where('email',$hubUsers->email_address)->where('role_id',5)->first();
		if(empty($user_data))
        {
            Session::put('error', 'This account is not verified, Please ask user to check your email');
            return redirect()->route('micro-hub.users.index');
        }
//        $hub_id = '';
//        $hub_select_data = '';
        /*if (is_null($user_data))
        {
            $hub_id = '';
            $hub_select_data = '';
        }
        else
        {
            $hub_select_data = Hubs::where('id',$user_data->hub_id)->first();

            $hub_id = $hub_select_data->parent_hub_id;
        }*/

        $hub_id = '';
        $hub_select_data = '';
        $hub_process = '';
        $assignedJoey = [];
        $assignedJobs = [];
        $hub_process_in_active = [];

        if (!is_null($user_data))
        {

            if (isset($user_data->hub_id))
            {
                //dd('yes');
                $hub_select_data = Hubs::where('id',$user_data->hub_id)->first();
                $hub_process = HubProcess::where('hub_id',$user_data->hub_id)->where('is_active',1)->pluck('process_id')->toarray();
                //$hub_id = $hub_select_data->parent_hub_id;
                $assignedJoey = MicroHubJoeyAssign::where('hub_id',$user_data->hub_id)->pluck('joey_id')->toarray();
                $assignedJobs = MiJobsAssign::where('hub_id',$user_data->hub_id)->pluck('mi_job_id')->toarray();

                //for request tag
                $process_id = MicroHubPermission::where('micro_hub_user_id', $user_data->id)->pluck('hub_process_id')->toarray();
                $hubProcessInActive = HubProcess::whereIn('id', $process_id)->where('is_active',0)->pluck('process_id')->toArray();
                $deliveryProcessInActive = DeliveryProcessType::whereIn('id', $hubProcessInActive)->pluck('process_label')->toArray();
                $hub_process_in_active = $deliveryProcessInActive;

            }

        }

        //Getting Hub Data
        //$hub_data = Hubs::whereNull('parent_hub_id')->get();

        //Getting Delivery Process Type
        $deliveryProcessType = DeliveryProcessType::whereNull('deleted_at')->get();

        //Mi Jobs List
        $miJobs = MiJobs::where('type','micro_hub_mid_mile')->get();

        $joeys = JoeyDocumentVerification::whereNull('deleted_at')->get(['id','first_name','last_name']);

        return view('admin.micro-hub.microHubUsers.hub-user-profile-update',
            compact(
                'hub_user',
                'hubUsers',
                'deliveryProcessType',
                'hub_select_data',
                'hub_process',
                'joeys',
                'assignedJoey',
                'user_data',
                'hub_process_in_active',
                'miJobs',
                'assignedJobs'
            )
        );
    }

    public function profileStatusUpdate(UpdateHubRequest $request, JoeycoUsers $hub_user)
    {

        DB::beginTransaction();
        try {
            $url = $request->url;
            //Getting Data From Ajax Request
            $update_address = $request->val;
            $latitude = str_replace(".", "", $request->address_latitude);
            $latitudes = (strlen($latitude) > 10) ? (int)substr($latitude, 0, 8) : (int)$latitude;
            $longitude = str_replace(".", "", $request->address_longitude);
            $longitudes = (strlen($longitude) > 10) ? (int)substr($longitude, 0, 9) : (int)$longitude;


            $microHubUser = JoeycoUsers::find($request->user_id);
            $joeyco_user = JoeycoUsers::where('id', $request->user_id)->update([
                'full_name' => $request->full_name,
                'phone_no' => $request->phone_no,
                'city' => isset($request->address_city) ? $request->address_city : '',
                'state' => $request->address_state,
                'street' => $request->address_street,
                'address' => $request->address,
                'user_phone' => $request->personal_phone_no,
                'user_city' => isset($request->personal_address_city) ? $request->personal_address_city : '',
                'user_state' => $request->personal_address_state,
                'user_street' => $request->personal_address_street,
                'user_address' => $request->personal_address,
                'email_address' => $request->email_address
            ]);

            /*$HubUserCreate = [
                'role_id' => 5,
                'full_name' => $request->full_name,
                'email' => $request->email_address,
                'phone' => $request->phone_no,
                'address' => $request->address,
                'password'=> $microHubUser->password,
                'hub_id'=> $hub->id,
                'micro_sub_admin'=> 1

            ];*/
            $HubUserCreate = DashboardUsers::where('email', $request->email_address)->where('role_id',5)->update([
                //'role_id' => 5,
                'full_name' => $request->full_name,
                //'email' => $request->email_address,
                'phone' => $request->phone_no,
                'address' => $request->address,
                'status' => $request->is_active
                //'password'=> $microHubUser->password,
                //'hub_id'=> $hub->id,
                //'micro_sub_admin'=> 1
            ]);
            //$user_date = $this->DashboardUsersRepository->create($HubUserCreate);
            $user_date = DashboardUsers::where('email', $request->email_address)->where('role_id',5)->first();

            if (empty($user_date))
            {
                Session::put('error', 'User not registered  !');

                return redirect()->route('micro-hub.users.index');
            }

            $consolidated_center = Hubs::where('is_consolidated',1)->pluck('city__id')->toArray();
            $userHub = $user_date->hub_id;
            $getHubCity = Hubs::whereIn('id',[$userHub])->pluck('city__id')->toArray();

            $cityExist = array_intersect($consolidated_center,$getHubCity);

            if(!empty($cityExist) && $request->is_consolidated == 1)
            {
                //Session::put('error', 'Sorry The Consolidated Center Already Assign To Another Hub Of Your City!');
                return redirect($url)->with('error','Sorry The Consolidated Center Already Assign To Another Hub Of Your City !');
            }
			// if (!isset($request->address_city))
           // {
           //     return redirect()
            //        ->back()
           //         ->with('error', 'Address not valid');
           // }
            //Getting All Cities
            $cities = Cities::where('name',$request->address_city)->
            orWhere('name','')->first();

            //Getting Location Data
            //$Location_data = Locations::where('city_id',$cities->id)->first();
			/*$HubCreate = [
                'title' => $request->title,
                //'parent_hub_id' => $request->hub_id,
                'address' => $request->address,
                'hub_latitude' => $latitudes,
                'hub_longitude'=> $longitudes,
                'city__id'=> $cities->id,
                'state__id'=> $cities->state_id,
                'country__id'=> $cities->country_id,
                'postal__code'=> $request->address_postal_code,
            ];
            $hub = $this->HubRepository->create($HubCreate);*/
            if($request->address_latitude == null && $request->address_longitude == null && $request->address_latitude == '' && $request->address_longitude == '')
            {
                return redirect($url)->with('error','Incorrect Address!');
            }
            if($request->address_postal_code == null && $request->address_postal_code == '')
            {
                return redirect($url)->with('error','Incorrect Address!');
            }
            $isConsolidated = [
				'title' => $request->full_name,
				'is_consolidated'=>$request->is_consolidated,
				'address' => $request->address,
                'hub_latitude' => $request->address_latitude,
                'hub_longitude'=> $request->address_longitude,
                'city__id'=> $cities->id,
                'state__id'=> $cities->state_id,
                'country__id'=> $cities->country_id,
                'postal__code'=> $request->address_postal_code,
			];
            Hubs::where('id',$userHub)->update($isConsolidated);
			if(isset($request->address_latitude) && isset($request->address_city))
            {
                $address_data = Cities::where('name',$request->address_city)->first();
                $hubAddress = [
                    'address' => $request->address,
                    'hub_latitude' => $request->address_latitude,
                    'hub_longitude' => $request->address_longitude,
                    'city__id' => $address_data->id,
                    'state__id' => $address_data->state_id,
                    'country__id' => $address_data->country_id,
                    'postal__code' => $request->address_postal_code,
                ];
                Hubs::where('id',$userHub)->update($hubAddress);

            }

            $permissionUpdate= [
                'deleted_at' =>  Carbon::now(),
            ];
            MicroHubPermission::where('micro_hub_user_id',$user_date->id)->update($permissionUpdate);
            HubProcess::where('hub_id',$user_date->hub_id)->update($permissionUpdate);
            if (isset($request->delivery_process_type)) {
                // now creating delivery process type data
                foreach ($request->delivery_process_type as $index => $delivery_process_id) {

                    // creating hub process
                    $HubProcessCreate = [
                        'hub_id' => $user_date->hub_id,
                        'process_id' => $delivery_process_id,
                        'is_active' => 1
                    ];
                    $hub_process_id = $this->HubProcessRepository->create($HubProcessCreate);

                    // creating hub process
                    $HubProcessPermissionCreate = [
                        'micro_hub_user_id' => $user_date->id,
                        'hub_process_id' => $hub_process_id->id
                    ];
                    $this->MicroHubPermissionInterface->create($HubProcessPermissionCreate);


                }
            }
            $hub_request = MicroHubRequest::where('jc_user_id', $request->user_id)->update([
                'area_radius' => $request->area_radius,
                'own_joeys' => $request->own_joeys,
                //'is_consolidated' => $request->is_consolidated,
                'status' => 1,
                "average_capacity" => $request->average_capacity,
                "minimum_capacity" => $request->minimum_capacity,
                "maximum_capacity" => $request->maximum_capacity
            ]);
            if (isset($request->joey)) {
                // now creating delivery process type data
                foreach ($request->joey as $index => $joey_id) {
                    // creating hub process
                    $MicroHubJoeyAssign = [
                        'hub_id' => $user_date->hub_id,
                        'joey_id' => $joey_id
                    ];
                    $this->MicroHubJoeyAssignRepository->create($MicroHubJoeyAssign);

                }
            }
            if (isset($request->miList)) {
                    $miJobAssign= [
                        'deleted_at' =>  Carbon::now(),
                    ];
                    MiJobsAssign::whereIn('mi_job_id',$request->miList)->where('hub_id',$user_date->hub_id)->update($miJobAssign);
                // now assigning jobs
                foreach ($request->miList as $index => $miList_id) {
                    // creating hub process
                    $MiJobsAssign = [
                        'hub_id' => $user_date->hub_id,
                        'mi_job_id' => $miList_id
                    ];
                    $this->MiAssignJobsRepositoryInterface->create($MiJobsAssign);

                }
            }

            DB::commit();
            return redirect($url)
                ->with('success', 'Hub Request Updated Successfully.');
        } catch (\Exception $e)
        {
            DB::rollback();

            Session::put('error', 'Sorry something went wrong. please try again later !');
            return redirect($url)->with('error','Sorry something went wrong. please try again later ! ');
            //return redirect()->route('micro-hub.users.index');
        }


    }

    public function hubProfileEdit(Request $request, $hub_user)
    {


        $hubUsers = JoeycoUsers::join('micro_hub_request', 'jc_users.id', '=', 'micro_hub_request.jc_user_id')
            ->where('jc_users.id',$hub_user)
            ->first();

        $user_data =  DashboardUsers::where('email',$hubUsers->email_address)->where('role_id',5)->first();


        $hub_id = '';
        $hub_select_data = '';
        $hub_process = '';
        $assignedJoey = [];
        $assignedJobs = [];
        $hub_process_in_active = [];

        if (!is_null($user_data))
        {
            if (isset($user_data->hub_id))
            {

                //dd('yes');
                $hub_select_data = Hubs::where('id',$user_data->hub_id)->first();
                $hub_process = HubProcess::where('hub_id',$user_data->hub_id)->where('is_active',1)->pluck('process_id')->toarray();
                //$hub_id = $hub_select_data->parent_hub_id;
                $assignedJoey = MicroHubJoeyAssign::where('hub_id',$user_data->hub_id)->pluck('joey_id')->toarray();
                $assignedJobs = MiJobsAssign::where('hub_id',$user_data->hub_id)->pluck('mi_job_id')->toarray();

                //for request tag
                $process_id = MicroHubPermission::where('micro_hub_user_id', $user_data->id)->pluck('hub_process_id')->toarray();
                $hubProcessInActive = HubProcess::whereIn('id', $process_id)->where('is_active',0)->pluck('process_id')->toArray();
                $deliveryProcessInActive = DeliveryProcessType::whereIn('id', $hubProcessInActive)->pluck('process_label')->toArray();
                $hub_process_in_active = $deliveryProcessInActive;

            }
        }

        //Getting Hub Data
        //$hub_data = Hubs::whereNull('parent_hub_id')->get();

        //Getting Delivery Process Type
        $deliveryProcessType = DeliveryProcessType::whereNull('deleted_at')->get();

        //Mi Jobs List
        $miJobs = MiJobs::where('type','micro_hub_mid_mile')->get();

        $joeys = JoeyDocumentVerification::whereNull('deleted_at')->get(['id','first_name','last_name']);

        return view('admin.micro-hub.microHubUsers.hub-user-profile-update',
            compact(
                'hub_user',
                'hubUsers',
                //'hub_data',
                'deliveryProcessType',
               // 'hub_id',
                'hub_select_data',
                'hub_process',
                'joeys',
                'assignedJoey',
                'user_data',
                'hub_process_in_active',
                'miJobs',
                'assignedJobs'
            )
        );
    }

}
