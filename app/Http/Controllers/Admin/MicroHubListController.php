<?php

namespace App\Http\Controllers\Admin;

use App\Classes\RestAPI;
use App\Models\DashboardUsers;
use App\Models\DeliveryProcessType;
use App\Models\HubPostalCode;
use App\Models\HubProcess;
use App\Models\JoeycoUsers;
use App\Models\MicroHubPermission;
use App\Models\ZonesRouting;
use App\Models\ZoneTypes;
use App\Repositories\Interfaces\HubPostalCodeRepositoryInterface;
use App\Repositories\Interfaces\SlotPostalCodeRepositoryInterface;
use App\Repositories\Interfaces\ZonesRoutingRepositoryInterface;
use App\Repositories\Interfaces\HubProcessRepositoryInterface;
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


class MicroHubListController extends Controller
{

    private $HubPostalCodeRepository;
    private $ZonesRoutingRepository;
    private $SlotPostalCodeRepository;
    private $HubProcessRepository;
    private $MicroHubPermissionInterface;

    public function __construct(HubPostalCodeRepositoryInterface $HubPostalCodeRepositoryInterface,
                                ZonesRoutingRepositoryInterface $ZonesRoutingRepositoryInterface,
                                SlotPostalCodeRepositoryInterface $SlotPostalCodeRepositoryInterface,
                                HubProcessRepositoryInterface $HubProcessRepositoryInterface,
                                MicroHubPermissionRepositoryInterface $MicroHubPermissionInterface)
    {
        $this->HubPostalCodeRepository = $HubPostalCodeRepositoryInterface;
        $this->ZonesRoutingRepository = $ZonesRoutingRepositoryInterface;
        $this->SlotPostalCodeRepository = $SlotPostalCodeRepositoryInterface;
        $this->HubProcessRepository = $HubProcessRepositoryInterface;
        $this->MicroHubPermissionInterface = $MicroHubPermissionInterface;
        //$this->middleware('guest:web')->except('logout');
        parent::__construct();

    }

    /**
     * Index action
     *
     */
    public function index()
    {
        return backend_view('micro-hub.microHubUsers.approved-micro-hub.index');
    }

    /**
     * Call DataTable For List
     *
     */
    public function data(DataTables $datatables, Request $request): JsonResponse
    {

        $query = JoeycoUsers::select('jc_users.*','dashboard_users.id as dashboard_id', 'micro_hub_request.area_radius', 'micro_hub_request.own_joeys', 'micro_hub_request.status')
            ->join('micro_hub_request', 'jc_users.id', '=', 'micro_hub_request.jc_user_id')
            ->join('dashboard_users', 'jc_users.email_address', '=', 'dashboard_users.email')
            ->whereNull('dashboard_users.deleted_at')
            ->where('dashboard_users.role_id',5)
            ->whereNull('micro_hub_request.deleted_at')
            ->where('micro_hub_request.status', 1);

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('area_radius', static function ($record) {
                if (isset($record->area_radius)) {
                    return $record->area_radius . ' Sq-yard';
                }
                return '';
            })
            ->editColumn('postal_code', static function ($record) {
                if (isset($record->dashboardUser)) {
                    return backend_view('micro-hub.microHubUsers.approved-micro-hub.sub-views.postal-code-detail-box', compact('record'));
                }
                return '';

            })
            ->editColumn('own_joeys', static function ($record) {
                if ($record->own_joeys == 1) {
                    return 'Yes';
                } else {
                    return 'No';
                }
            })
            ->editColumn('action', static function ($record) {
                // $process_id = (HubProcess::where('hub_id',$record->dashboardUser->hub_id)->pluck('process_id')->toarray()) ? HubProcess::where('hub_id',$record->dashboardUser->hub_id)->pluck('process_id')->toarray() : 0;
                //Getting Delivery Process Type
                $deliveryProcessTypes = DeliveryProcessType::whereNull('deleted_at')->get();
                $process_id = MicroHubPermission::where('micro_hub_user_id', $record->dashboard_id)->pluck('hub_process_id')->toarray();
                $hubProcess = HubProcess::whereIn('id', $process_id)->where('is_active',1)->pluck('process_id')->toArray();
                $deliveryProcess = DeliveryProcessType::whereIn('id', $hubProcess)->pluck('process_label')->toArray();

                //for request tag
                $hubProcessInActive = HubProcess::whereIn('id', $process_id)->where('is_active',0)->pluck('process_id')->toArray();
                $deliveryProcessInActive = DeliveryProcessType::whereIn('id', $hubProcessInActive)->pluck('process_label')->toArray();

                $hub_process = $deliveryProcess;
                $hub_process_in_active = $deliveryProcessInActive;
                return backend_view('micro-hub.microHubUsers.approved-micro-hub.action', compact('record', 'hub_process','deliveryProcessTypes','hub_process_in_active'));

            })
            ->rawColumns(['status', 'phone', 'link', 'action'])
            ->make(true);
    }

    public function hubPermissionUpdate(Request $request)
    {
        DB::beginTransaction();
        try {
            $userId = $request->user_id;
            $userData = DashboardUsers::where('id',$userId)->where('role_id',5)->first();
            $permissionUpdate= [
                'deleted_at' =>  Carbon::now(),
            ];
            MicroHubPermission::where('micro_hub_user_id',$userId)->update($permissionUpdate);
            HubProcess::where('hub_id',$userData->hub_id)->update($permissionUpdate);
            // now creating delivery process type data
            foreach ($request->delivery_process_type as $index => $delivery_process_id) {

                // creating hub process
                $HubProcessCreate = [
                    'hub_id' => $userData->hub_id,
                    'process_id' => $delivery_process_id,
                    'is_active' => 1
                ];
                $hub_process_id = $this->HubProcessRepository->create($HubProcessCreate);

                // creating hub process
                $HubProcessPermissionCreate = [
                    'micro_hub_user_id' => $userId,
                    'hub_process_id' => $hub_process_id->id
                ];
                $this->MicroHubPermissionInterface->create($HubProcessPermissionCreate);



            }
            DB::commit();
            return redirect()
                ->route('micro-hub.approved.index')
                ->with('success', 'Permission Updated Successfully');
        } catch (\Exception $e) {
            DB::rollback();

            Session::put('error', 'Sorry something went wrong. please try again later !');

            return redirect()->route('micro-hub.approved.index');
        }
    }

    /**
     * store action
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createPostalCode(Request $request)
    {
        DB::beginTransaction();
        try {

            $dashboard_user_data = DashboardUsers::where('email', $request->email_address)->where('role_id',5)->first();

            if (isset($dashboard_user_data->hub_id)) {
                $this->HubPostalCodeRepository->deletePostalCode($dashboard_user_data->hub_id);
            }

            // now creating delivery process type data
            foreach ($request->postal as $index => $PostalCode) {

                // creating hub process
                $HubPostalCodeCreate = [
                    'hub_id' => $dashboard_user_data->hub_id,
                    'postal_code' => $PostalCode
                ];

                $this->HubPostalCodeRepository->create($HubPostalCodeCreate);

            }

            DB::commit();
            return redirect()
                ->route('micro-hub.approved.index')
                ->with('success', 'Postal Code added successfully.');
        } catch (\Exception $e) {
            DB::rollback();

            Session::put('error', 'Sorry something went wrong. please try again later !');

            return redirect()->route('micro-hub.approved.index');
        }


    }

    /**
     * store action
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createZone(Request $request)
    {
        DB::beginTransaction();
        try {

            $dashboard_user_data = DashboardUsers::where('email', $request->email_address)->where('role_id',5)->first();


            $hub_id = (isset($dashboard_user_data->hub_id)) ? $dashboard_user_data->hub_id : 0;
            $zone_routing_data = $this->ZonesRoutingRepository->updateOrCreate($hub_id, [
                'hub_id' => $dashboard_user_data->hub_id,
                'title' => $request->title,
                'zone_type' => $request->zone_type,
            ]);
            if (isset($zone_routing_data)) {
                $this->SlotPostalCodeRepository->deletePostalCode($zone_routing_data->id);
            }
            //dd($zone_routing_data);
            //dd([$zone_routing_data,$hub_id,$request->postal]);
            // now creating delivery process type data
            foreach ($request->postal as $index => $PostalCode) {

                // creating hub process
                $HubPostalCodeCreate = [
                    'zone_id' => $zone_routing_data->id,
                    'postal_code' => strtoupper($PostalCode)
                ];

                $this->SlotPostalCodeRepository->create($HubPostalCodeCreate);

            }

            DB::commit();
            return redirect()
                ->route('micro-hub.approved.index')
                ->with('success', 'Zone added successfully.');
        } catch (\Exception $e) {
            DB::rollback();

            Session::put('error', 'Sorry something went wrong. please try again later !');

            return redirect()->route('micro-hub.approved.index');
        }


    }

    /**
     * Render Model zone create table view
     */
    public function zoneCreateModelHtmlRender(Request $request)
    {
        $request_data = $request->all();
        $dashboard_user = DashboardUsers::where('email', $request_data)->where('role_id',5)->pluck('hub_id')->toArray();
        $zones_routing = ZonesRouting::where('hub_id', $dashboard_user)->first();
        $zoneType = ZoneTypes::whereNull('deleted_at')->get();
        $zone_type = '';
        if (!is_null($zones_routing)) {
            $zone_type = $zones_routing->zone_type;
        }

        $html = backend_view('micro-hub.microHubUsers.approved-micro-hub.sub-views.ajax-render-view-zone-create-model',
            compact(
                'zoneType',
                'request_data',
                'zones_routing',
                'zone_type'

            )
        )->render();

        return response()->json(['status' => true, 'html' => $html]);
    }

    /**
     * Render Model postal code create table view
     */
    public function postalCodeCreateModelHtmlRender(Request $request)
    {
        $request_data = $request->all();
        $dashboard_user = DashboardUsers::where('email', $request_data['email_address'])->where('role_id',5)->pluck('hub_id')->toArray();
        $hub_postal_code = HubPostalCode::where('hub_id', $dashboard_user)->get();

        $postal_code = '';
        if (!is_null($hub_postal_code)) {
            $postal_code = $hub_postal_code;
        }

        $html = backend_view('micro-hub.microHubUsers.approved-micro-hub.sub-views.ajax-render-view-postal-code-create-model',
            compact(
                'request_data',
                'postal_code'

            )
        )->render();

        return response()->json(['status' => true, 'html' => $html]);
    }

    /**
     * Not Trained List Index
     *
     */
    public function notApprovedList()
    {
        return backend_view('micro-hub.microHubUsers.notApproved-list.index');
    }

    /**
     * Call DataTable For Not Approved List
     *
     */
    public function notApprovedData(DataTables $datatables, Request $request): JsonResponse
    {

        $query = JoeycoUsers::whereHas('microHubRejectedUserList');

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('area_radius', static function ($record) {
                if (isset($record->microHubUserList->area_radius)) {
                    return $record->microHubUserList->area_radius;
                }
                return '';
            })
            ->editColumn('own_joeys', static function ($record) {
                if ($record->microHubUserList->own_joeys == 1) {
                    return 'Yes';
                } else {
                    return 'No';
                }
            })
            ->editColumn('status', static function ($record) {
                return backend_view('micro-hub.microHubUsers.notApproved-list.status', compact('record'));

            })
            ->rawColumns(['status', 'phone', 'link', 'action'])
            ->make(true);
    }

    /**
     * document approved action
     *
     */
    public function documentApprovedIndex()
    {
        return backend_view('micro-hub.microHubUsers.document-approved.index');
    }

    /**
     * Call DataTable For List
     *
     */
    public function documentApprovedData(DataTables $datatables, Request $request): JsonResponse
    {

        $query = JoeycoUsers::whereHas('userDocumentsApproved');

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('area_radius', static function ($record) {
                if (isset($record->area_radius)) {
                    return $record->area_radius . ' Sq-yard';
                }
                return '';
            })
            ->rawColumns(['status', 'phone', 'link'])
            ->make(true);
    }

    /**
     * document not approved action
     *
     */
    public function documentNotApprovedIndex()
    {
        return backend_view('micro-hub.microHubUsers.documentNot-approved.index');
    }

    /**
     * Call DataTable For List
     *
     */
    public function documentNotApprovedData(DataTables $datatables, Request $request): JsonResponse
    {

        $query = JoeycoUsers::whereHas('userDocumentsNotApproved');

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->editColumn('area_radius', static function ($record) {
                if (isset($record->area_radius)) {
                    return $record->area_radius . ' Sq-yard';
                }
                return '';
            })
            ->editColumn('action', static function ($record) {
                return backend_view('micro-hub.microHubUsers.documentNot-approved.attachment', compact('record'));
            })
            ->rawColumns(['status', 'phone', 'link'])
            ->make(true);
    }

    /**
     * Not Trained List Index
     *
     */
    public function notTrainedList()
    {
        return backend_view('micro-hub.microHubUsers.notTrained-list.index');
    }

    /**
     * Call DataTable For Not Trained List
     *
     */
    public function notTrainedData(DataTables $datatables, Request $request): JsonResponse
    {

        $query = JoeycoUsers::whereDoesntHave('microHubUserTrainingSeen');

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->rawColumns(['status', 'phone', 'link', 'action'])
            ->make(true);
    }

    /**
     * Quiz Pending List Index
     *
     */
    public function quizPendingList()
    {
        return backend_view('micro-hub.microHubUsers.quiz-pending-list.index');
    }

    /**
     * Call DataTable For Quiz Pending List
     *
     */
    public function quizPendingData(DataTables $datatables, Request $request): JsonResponse
    {

        $query = JoeycoUsers::whereDoesntHave('microHubUserPendingQuiz');

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->rawColumns(['status', 'phone', 'link', 'action'])
            ->make(true);
    }

    /**
     * Quiz Passed List Index
     *
     */
    public function quizPassedList()
    {
        return backend_view('micro-hub.microHubUsers.quizPassed-list.index');
    }

    /**
     * Call DataTable For Quiz Passed List
     *
     */
    public function quizPassedData(DataTables $datatables, Request $request): JsonResponse
    {

        $query = JoeycoUsers::whereHas('microHubUserAttemptedQuiz');

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->rawColumns(['status', 'phone', 'link', 'action'])
            ->make(true);
    }

    /**
     * Document Not Uploaded List Index
     *
     */
    public function documentNotUploaded()
    {
        return backend_view('micro-hub.microHubUsers.documentNotUploaded.index');
    }

    /**
     * Call DataTable For Document Not Uploaded List
     *
     */
    public function documentNotUploadedData(DataTables $datatables, Request $request): JsonResponse
    {

        $query = JoeycoUsers::whereDoesntHave('microHubUserDocumentsNotUploaded');

        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            ->rawColumns(['status', 'phone', 'link', 'action'])
            ->make(true);
    }

}
