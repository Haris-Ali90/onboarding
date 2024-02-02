<?php

namespace App\Http\Controllers\Admin;

use App\Models\JoeyComplaints;
use App\Models\JoeyDocumentVerification;
use Illuminate\Http\Request;
use Auth;
use Session;
use Validator;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;


class JoeysComplaintsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
        parent::__construct();
    }

    /**
     * Index action
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {

        /*$complaints = JoeyDocumentVerification::with('joeyComplaints')
            ->whereNull('joeys.deleted_at')
            ->get();*/
        $data = $request->all();
        $id = isset($data['id'])? $request->get('id'):'';
        $selectjoey = isset($data['joey'])?$request->get('joey'):'';
        $type = isset($data['type'])?$request->get('type'):'';
        $joeys = JoeyDocumentVerification::whereNull('deleted_at')->get(['id','first_name','last_name']);
        $joey_document = JoeyComplaints::distinct()
                                        ->pluck('type')
                                        ->toArray();
        return backend_view('joey-complaints.index',compact('joeys','selectjoey','id','joey_document','type'));
    }

    public function data(DataTables $datatables, Request $request): JsonResponse
    {
        /*$query = JoeyDocumentVerification::with('joeyComplaints')
            ->has('joeyComplaints')
            ->whereNull('joeys.deleted_at');*/
        $query = JoeyComplaints::with('ComplaintsJoeys')
            ->has('ComplaintsJoeys')
            ->whereNull('complaints.deleted_at');
        if ($request->get('id')) {
            $query = $query->where('complaints.joey_id',$request->get('id'));
        }
        if ($request->get('joey')) {
            $query = $query->where('complaints.joey_id',$request->get('joey'));
        }
        if ($request->get('type')) {
            $query = $query->where('complaints.type',$request->get('type'));
        }
        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
			->addColumn('joey_id', static function ($record) {
                return $record->ComplaintsJoeys->id;

            })
            ->addColumn('first_name', static function ($record) {
                return $record->ComplaintsJoeys->first_name . ' ' . $record->ComplaintsJoeys->last_name;

            })
            ->addColumn('Complaints', static function ($record) {
                return $record->description ;
            })
            ->addColumn('Type', static function ($record) {
                return $record->type ;
            })
            ->addColumn('status', static function ($record) {
                return backend_view('joey-complaints.status', compact('record'));
            })
            ->make(true);
    }

    /**
     *
     *
     */
    public function statusUpdate(Request $request){

         $statusData=$request->all();

         $updateStatus = [
             'status' => $statusData['status'],
         ];
        JoeyComplaints::where('id' , $statusData['id'])->update($updateStatus);
         return 'true';

    }

}
