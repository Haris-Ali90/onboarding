<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cities;
use App\Models\DashboardUsers;
use App\Models\Hubs;
use App\Models\HubsAssign;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\HubAssignRepositoryInterface;
class MicroHubAssignController extends Controller
{

    private $HubAssignRepositoryInterface;
    public function __construct(HubAssignRepositoryInterface $HubAssignRepositoryInterface)
    {

        parent::__construct();
        $this->HubAssignRepositoryInterface = $HubAssignRepositoryInterface;
    }

    public function index()
    {
        return backend_view('micro-hub.micro-hub-assign.index');
    }

    public function data(DataTables $datatables, Request $request): JsonResponse
    {
        $query =  DashboardUsers::where('micro_sub_admin','1');

        return $datatables->eloquent($query)

            ->setRowId(static function ($record) {

                return $record->id;
            })
            ->editColumn('created_at', static function ($record) {
                return $record->created_at;
            })
            ->addColumn('userType', static function ($record) {
                if ($record->userType) {
                    return ucwords(str_replace('_', ' ', $record->userType));
                }
                return '';
            })
            ->addColumn('action', static function ($record) {
                return backend_view('micro-hub.micro-hub-assign.action', compact('record'));
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function microHubAssignEdit($id)
    {

        $id=base64_decode($id);
        $userData = DashboardUsers::find($id);
        $havePermission = HubsAssign::where('user_id',$id)->whereNull('deleted_at')->pluck('hub_id')->toArray();
        $selectedCity = Hubs::whereIn('id',$havePermission)->pluck('city__id')->toArray();
        $selectedCity = implode(',',$selectedCity);
        $selectedPermission = implode(',',$havePermission);
        //dd($selectedPermission);
        $allHub = Hubs::get();

        $cities = Cities::get();
        //$hubAssignID = HubsAssign::where('user_id',$id)->pluck('hub_id')->toArray();
        return backend_view('micro-hub.micro-hub-assign.edit',compact('userData','allHub','selectedPermission','cities','selectedCity'));
    }

    public function microHubAssignUpdate(Request $request,$id)
    {

       DB::beginTransaction();
       try {
           $hubAssignUpdate= [
               'deleted_at' =>  Carbon::now(),
           ];

           if ($request->userType == 'city_incharge') {

               $hud_ids = Hubs::where('city__id',$request->city)->pluck('id')->toArray();


               HubsAssign::where('user_id', $id)->update($hubAssignUpdate);

               if (isset($hud_ids)) {

                   // now creating delivery process type data
                   foreach ($hud_ids as $index => $hubPermission) {


                       // creating hub permission
                       $HubAssignPermission = [
                           'hub_id' => $hubPermission,
                           'user_id' => $id
                       ];
                       $this->HubAssignRepositoryInterface->create($HubAssignPermission);


                   }
               }
               $userType = [
                   'userType' => $request->userType,
               ];
               DashboardUsers::where('id', $id)->update($userType);
           }
           else
           {
               HubsAssign::where('user_id', $id)->update($hubAssignUpdate);
               if (isset($request->hubPermission)) {
                   // now creating delivery process type data
                   foreach ($request->hubPermission as $index => $hubPermission) {
                       // creating hub process
                       $HubAssignPermission = [
                           'hub_id' => $hubPermission,
                           'user_id' => $id
                       ];
                       $this->HubAssignRepositoryInterface->create($HubAssignPermission);


                   }
               }
               $userType = [
                   'userType' => $request->userType,
               ];
               DashboardUsers::where('id', $id)->update($userType);
           }
            DB::commit();
            return redirect()
                ->route('micro-hub-assign.index')
                ->with('success', 'Hub Assign Successfully.');
        } catch (\Exception $e)
        {
            DB::rollback();

            Session::put('error', 'Sorry something went wrong. please try again later !');
            return redirect()->route('micro-hub-assign.index');
            //return redirect()->route('micro-hub.users.index');
        }
    }

    public function microCityHubAssignUpdate(Request $request)
    {
        //$request_data = $request->all();
//dd($request_data);

        //$selectedHub = Hubs::whereIn('id',$hubAssign)->pluck();
        //dd($selectedHub);

        $hub_data = Hubs::where('city__id', $request->id)->get();

        //$dashboard_user = DashboardUsers::where('email', $request_data)->where('role_id',5)->pluck('hub_id')->toArray();
        //$zones_routing = ZonesRouting::where('hub_id', $dashboard_user)->first();
        //$zoneType = ZoneTypes::whereNull('deleted_at')->get();
        //$zone_type = '';
        //if (!is_null($zones_routing)) {
        //    $zone_type = $zones_routing->zone_type;
        //}
//
        //$html = backend_view('micro-hub.microHubUsers.approved-micro-hub.sub-views.ajax-render-view-zone-create-model',
        //    compact(
        //        'zoneType',
        //        'request_data',
        //        'zones_routing',
        //        'zone_type'
//
        //    )
        //)->render();
//
        return response()->json([ 'data' => $hub_data]);
    }

}
