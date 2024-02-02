<?php

namespace App\Http\Controllers\Admin;

use App\Classes\RestAPI;
use App\Models\Documents;
use App\Models\JoeycoUsers;
use App\Models\MicroHubUserDocument;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class MicroHubDocumentVerificationController extends Controller
{


    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Index action
     *
     */
    public function documentVerification(Request $request)
    {
        $data = $request->all();
        $id = isset($data['id']) ? $request->get('id') : '';
        $selectUser = isset($data['user']) ? $request->get('user') : '';
        $selectStatus = isset($data['documentStatus']) ? $request->get('documentStatus') : '';
        $type = isset($data['type']) ? $request->get('type') : '';
        $users = JoeycoUsers::get(['id', 'full_name']);
        $joey_document = Documents::where('user_type', 'micro_hub')->pluck('document_name')->toArray();
        return backend_view('micro-hub.document-verification.index', compact('users', 'id', 'selectUser', 'type', 'joey_document', 'selectStatus'));
    }

    /**
     * Call DataTable For List
     *
     */
    public function documentVerificationData(DataTables $datatables, Request $request): JsonResponse
    {
        $query = JoeycoUsers::select('microhub_documents.id', 'full_name', 'jc_users_id', 'microhub_documents.document_type', 'microhub_documents.exp_date', 'microhub_documents.document_data', 'microhub_documents.is_approved')
            ->join('microhub_documents', 'jc_users.id', '=', 'microhub_documents.jc_users_id')
            ->join('document_types', 'microhub_documents.document_type_id', '=', 'document_types.id')
            ->whereNull('microhub_documents.deleted_at')
            ->where('document_types.user_type', 'micro_hub')
            ->whereNull('document_types.deleted_at');
        if ($request->get('id')) {
            $query = $query->where('microhub_documents.jc_users_id', $request->get('id'));
        }
        if ($request->get('user')) {
            $query = $query->where('microhub_documents.jc_users_id', $request->get('user'));
        }
        if ($request->get('type')) {
            $query = $query->where('microhub_documents.document_type', $request->get('type'));
        }
        if ($request->documentStatus == 0 && $request->documentStatus != null) {
            $query = $query->where('microhub_documents.is_approved', $request->documentStatus);
        } elseif ($request->documentStatus == 1) {
            $query = $query->where('microhub_documents.is_approved', $request->documentStatus);
        } elseif ($request->documentStatus == 2) {
            $query = $query->where('microhub_documents.is_approved', $request->documentStatus);
        }
        return $datatables->eloquent($query)
            ->setRowId(static function ($record) {
                return $record->id;
            })
            //->addIndexColumn()
            ->addColumn('full_name', static function ($record) {

                if ($record) {
                    return $record->full_name . ' (' . $record->jc_users_id . ')';
                }
                return '';
            })
            ->editColumn('document_type', static function ($record) {
                return $record->document_type;
            })
            ->editColumn('exp_date', static function ($record) {
                if (!empty($record->exp_date) && $record->exp_date != '(NULL)' && $record->exp_date < date('Y-m-d')) {
                    return backend_view('micro-hub.document-verification.expiry', compact('record'));
                } else {
                    return $record->exp_date;
                }

            })
            ->editColumn('document_data', static function ($record) {
                return backend_view('micro-hub.document-verification.image', compact('record'));

            })
            ->editColumn('is_approved', static function ($record) {
                return backend_view('micro-hub.document-verification.status', compact('record'));

            })
            ->addColumn('action', static function ($record) {
                return backend_view('micro-hub.document-verification.action', compact('record') );
            })
            ->make(true);
    }

    public function statusUpdate(Request $request)
    {
        DB::beginTransaction();
        try {

            $statusData = $request->all();

            $updateStatus = [
                'is_approved' => $statusData['status'],
            ];
            MicroHubUserDocument::where('id', $statusData['id'])->update($updateStatus);


            DB::commit();
            return 'true';
        } catch (\Exception $e) {
            DB::rollback();

            Session::put('error', 'Sorry something went wrong. please try again later !');

            return redirect()->route('micro-hub.approved.index');
        }

    }

    public function show($userDocumentVerification)
    {

        $userDocument=MicroHubUserDocument::where('jc_users_id',$userDocumentVerification)
            ->whereNull('microhub_documents.deleted_at')
            ->get();
        return view('admin.micro-hub.document-verification.show', compact('userDocumentVerification','userDocument'));
    }

    public function documentEdit($userDocumentVerification)
    {

        $document_type = Documents::where('user_type','micro_hub')->get();
        $userDocument=MicroHubUserDocument::where('jc_users_id',$userDocumentVerification)->get();

        $userDocument_data = [];
        // setting up data
        foreach($userDocument as $single_userDocument )
        {

            $userDocument_data[$single_userDocument->document_type_id] = $single_userDocument;
        }

        // dd($joeyDocumentVerification);
        return view('admin.micro-hub.document-verification.edit',  compact('userDocumentVerification','userDocument_data','document_type'));
    }

    public function documentUpdate($joey_document_verification,Request $request)
    {
        $document_ids = $request->document_id;
        $document_exp_date = $request->document_exp_date;
        $document_status = $request->document_status;
        $documents =  $request->document;
        $document_type_name = $request->document_type_name;
        $current_data = date('Y-m-d H:i:s');
        if(!empty($documents))
        {
            $selected_data = [];
            foreach ($documents as $key => $document) {

                $document_data ='';
                if(gettype($document) == 'object')
                {
                    $file = $document;
                    $fileName = $file->getClientOriginalName();
                    $file->move(backendUserFile(), $file->getClientOriginalName());
                    $document_data = url(backendUserFile() . $fileName);
                }
                else
                {
                    $document_data = $document;
                }

                // getting updating ids
                $id = $document_ids[$key];
                $document_old_status = 0;
                // deleting old data for duplication
                if($document_ids[$key] > 0)
                {
                    $joey_document_data = MicroHubUserDocument::where('id',$id)->first();
                    // copy old status if it not exist
                    $document_old_status = (isset($document_status[$key])) ? $document_status[$key] :$joey_document_data->is_approved;

                    $joey_document_data->deleted_at = $current_data;
                    $joey_document_data->save();

                }

                // duplication data
                $joey_document_data_new_data = MicroHubUserDocument::create([
                    "jc_users_id" =>$joey_document_verification,
                    "document_type" =>$document_type_name[$key],
                    "document_type_id" => $key,
                    "document_data" => $document_data,
                    "exp_date" => $document_exp_date[$key],
                    "is_approved" => $document_old_status
                ]);
            }

        }


        return redirect()->back()->with('success', 'Document updated successfully.');
    }

}
